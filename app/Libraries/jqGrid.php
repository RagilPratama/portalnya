<?php

namespace App\Libraries;
use DB;
class jqGrid
{
    
    protected $page;
    protected $total;
    protected $records;
    protected $rows;
    protected $currentpage;
    protected $nextpage;
    protected $previouspage;
    protected $status;
    protected $message;
    
    
    public function __construct($collection, $options=[])
    {
        $this->page = 1;
        $this->total = 0;
        $this->records = 0;
        $this->rows = [];        
        $this->status = 200;
        if (is_string($collection)) {
            $this->rawsql($collection, $options);
        } else {
            $this->data($collection, $options);
        }
    }
    
    private function data($collection, $options=[])
    {
        // ini_set('memory_limit','256M');
        $request = request();
        try {
            $request->rows = $request->rows ?? 1000;
            $page = $request->page ?? 1;
            $limit = $request->rows ?? 10;
            $offset = ($page-1) * $limit;
            if (is_array($collection)) {
                $collection = collect($collection);
            }
            $allrecords = $collection;
            
            //filtering search
            $filters = json_decode($request->filters);
            if (!is_null($filters) && !is_null($filters->rules) && count($filters->rules)>0 ) {
                $rules = [];
                foreach ($filters->rules as $rule) {
                    $rules[] = $rule->field;
                    // $allrecords = $allrecords->where($rule->field, 'like', '%'.$rule->data.'%');
                    $term = '%'.strtolower($rule->data).'%';
                    $allrecords = $allrecords->whereRaw('LOWER("' . $rule->field . '") LIKE ?', [$term]);
                }
            } elseif (!empty($request->search) && !empty($options['searchFields'])) {
                foreach ($options['searchFields'] as $field) {
                    $allrecords = $allrecords->orWhere($field, 'like', '%'.$request->search.'%');
                }
            }
            
            if (!is_null($request->sidx)) {
                $request->sord = $request->sord ?? 'asc';
                if (get_class($collection)=='Illuminate\Support\Collection') {
                    if (strtolower($request->sord)=='desc') {
                        $allrecords = $allrecords->sortByDesc($request->sidx);
                    } else {
                        $allrecords = $allrecords->sortBy($request->sidx);
                    }
                } else {
                    $allrecords = $allrecords->orderBy($request->sidx, $request->sord);
                }
            }
            
            $totalrecords = $allrecords->count();
            // pagination 
            if (empty($request->rows)) {
                $rows = get_class($collection)=='Illuminate\Support\Collection' ? $allrecords : $allrecords->get();
                $limit = $totalrecords;
            } else {
                if (get_class($collection)=='Illuminate\Support\Collection') {
                    $rows = $allrecords->slice($offset, $limit);
                } else {
                    $rows = $allrecords->offset($offset)->limit($limit)->get();
                }
            }
            
            $this->page = $page;
            $this->total = ceil($totalrecords / $limit);
            $this->records = $totalrecords;
            $this->rows = $rows;
            
            
            /*$parse_url = explode('?', url()->full());
            $urlquerystr = $parse_url[1] ?? '' ;
            parse_str($urlquerystr,  $qstr);
            $qstrprev = $qstrnext = $qstr;
            $qstrprev['page'] = $page-1;
            $qstrnext['page'] = $page+1;
            $this->currentpage = request()->path().'?'.http_build_query($qstr);
            $this->previouspage = $page==1 ? null :  request()->path().'?'.http_build_query($qstrprev);
            $this->nextpage = $page==$this->total ? null :  request()->path().'?'.http_build_query($qstrnext);*/
            
        } catch (\Exception $e) {
            $this->status = 500;
            $this->message = $e->getMessage();
        }
    }
    
    private function rawsql($sql, $options=[])
    {
        // ini_set('memory_limit','256M');
        $request = request();
        try {
            $request->rows = $request->rows ?? 1000;
            $page = $request->page ?? 1;
            $limit = $request->rows ?? 10;
            $offset = ($page-1) * $limit;
            $sidx = empty($request->sidx) ? '': '"'.$request->sidx.'"';// ?? 'id';
            $sord = $request->sord ?? 'asc';
            
            //filtering search
            $filters = json_decode($request->filters);
            if (!is_null($filters) && !is_null($filters->rules) && count($filters->rules)>0 ) {
                $rules = [];
                $wheres = [];
                foreach ($filters->rules as $rule) {
                    $rules[] = $rule->field;
                    $term = '%'.strtolower($rule->data).'%';
                    $wheres[] = "LOWER(\"{$rule->field}\") LIKE '{$term}'";
                }
                $wheres = implode(' AND ', $wheres);
                $sql = "SELECT * FROM ({$sql}) s WHERE {$wheres}";
            } elseif (!empty($request->search) && !empty($options['searchFields'])) {
                
            }
            
            $totalrecords = DB::select("SELECT count(*) cnt FROM ({$sql}) x")[0]->cnt;
            
            if (config('database.default')=='sqlsrv') {
                $numfrom = $offset + 1;
                $numto = $numfrom + $limit - 1;
                $sql = "SELECT * FROM (
                        SELECT *, ROW_NUMBER() OVER (ORDER BY {$sidx} {$sord}) AS RowNum
                        FROM ({$sql}) x 
                    ) AS xx
                    WHERE xx.RowNum BETWEEN {$numfrom} and {$numto}";
            } elseif (config('database.default')=='oracle') {
                $numfrom = $offset + 1;
                $numto = $numfrom + $limit - 1;
                
                if (!empty($sidx)){
                    $sql = "SELECT s.* FROM ({$sql}) s ORDER BY {$sidx} {$sord}";
                }
                
                $sql = "SELECT xx.* FROM ( 
                    SELECT  x.*, ROWNUM RNUM FROM ({$sql}) x 
                      WHERE ROWNUM <= 
                      {$numto} ) xx
                    WHERE RNUM  >= {$numfrom}";
            } else {
                
                if (!empty($sidx)){
                    $sql = "SELECT * FROM ({$sql}) x ORDER BY {$sidx} {$sord} LIMIT {$limit} OFFSET {$offset} ";
                } else {
                    $sql = "SELECT * FROM ({$sql}) x LIMIT {$limit} OFFSET {$offset} ";
                }
                
            }
            
            // debug($sql);exit;
            $rows = DB::select($sql);
            
            $this->page = $page;
            $this->total = ceil($totalrecords / $limit);
            $this->records = $totalrecords;
            $this->rows = $rows;
            
            
            /*$parse_url = explode('?', url()->full());
            $urlquerystr = $parse_url[1] ?? '' ;
            parse_str($urlquerystr,  $qstr);
            $qstrprev = $qstrnext = $qstr;
            $qstrprev['page'] = $page-1;
            $qstrnext['page'] = $page+1;
            $this->currentpage = request()->path().'?'.http_build_query($qstr);
            $this->previouspage = $page==1 ? null :  request()->path().'?'.http_build_query($qstrprev);
            $this->nextpage = $page==$this->total ? null :  request()->path().'?'.http_build_query($qstrnext);*/
            
        } catch (\Exception $e) {
            $this->status = 500;
            $this->message = $e->getMessage();
        }
    }
    
    public function get()
    {
        http_response_code($this->status);
        if ($this->status == 200) {
            return [
                'page' => $this->page,
                'total' => $this->total,
                'records' => $this->records,
                // 'links' => [
                    // 'previous' => $this->previouspage,
                    // 'current' => $this->currentpage,
                    // 'next' => $this->nextpage,
                // ],
                'rows' => $this->rows
            ];
        } else {
            return response($this->message)->header('Content-Type', 'text/plain');
        }
    }
    
}
