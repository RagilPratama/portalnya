<?php

namespace App\Libraries;
use DB;

class DataTable
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
        $request = request();
        try {
            $pagination = $request->pagination;
            $sort = $request->sort;
            $search = $request->input('query');
            
            $pagination['perpage'] = $pagination['perpage'] ?? 1000;
            $page = $pagination['page'] ?? 1;
            $limit = $pagination['perpage'] ?? 10;
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
                    $allrecords = $allrecords->where($rule->field, 'like', '%'.$rule->data.'%');
                }
            } elseif (!empty($search) && !empty($options['searchFields'])) {
                foreach ($options['searchFields'] as $field) {
                    $search = array_values($search);
                    $searchTerm = '%'.$search[0].'%';
                    $allrecords = $allrecords->orWhere($field, 'like', $searchTerm);
                    // $allrecords = $allrecords->orWhereRaw('LOWER('.$field.') LIKE ?', 'LOWER(\''.$searchTerm.'\')');
                }
            }
            
                // debug($allrecords->toSql());
                // debug($allrecords->getBindings());exit;
            if (!is_null($sort['field'])) {
                $sort['sort'] = $sort['sort'] ?? 'asc';
                if (get_class($collection)=='Illuminate\Support\Collection') {
                    if (strtolower($request->sord)=='desc') {
                        $allrecords = $allrecords->sortByDesc($sort['field']);
                    } else {
                        $allrecords = $allrecords->sortBy($sort['field']);
                    }
                } else {
                    $allrecords = $allrecords->orderBy($sort['field'], $sort['sort']);
                }
            }
            
            $totalrecords = $allrecords->count();
            
            // pagination 
            if (empty($pagination['perpage'])) {
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
            $this->perpage = $limit;
            $this->totalpages = ceil($totalrecords / $limit);
            $this->totalrecords = $totalrecords;
            $this->rows = $rows;
            
            
            // $parse_url = explode('?', url()->full());
            // $urlquerystr = $parse_url[1] ?? '' ;
            // parse_str($urlquerystr,  $qstr);
            // $qstrprev = $qstrnext = $qstr;
            // $qstrprev['page'] = $page-1;
            // $qstrnext['page'] = $page+1;
            // $this->currentpage = request()->path().'?'.http_build_query($qstr);
            // $this->previouspage = $page==1 ? null :  request()->path().'?'.http_build_query($qstrprev);
            // $this->nextpage = $page==$this->totalpages ? null :  request()->path().'?'.http_build_query($qstrnext);
            
        } catch (\Exception $e) {
            $this->status = 500;
            $this->message = $e->getMessage();
        }
    }
    
    private function rawsql($sql, $options=[])
    {
        // ini_set('memory_limit','256M');
        $request = request();
        // debug($request->order);exit;
        try {
            $limit = $request->input('length') ?? 10;
            $offset = $request->input('start');
            
            $columns = $request->input('columns');
            $idx = $request->input('order.0.column') ?? null;
            $sidx = is_null($idx) ? '' : $columns[$idx]['data'];
            $sord = $request->input('order.0.dir');
            $search = $request->input('search.value');
            //filtering search
            $filters = json_decode($request->filters);
            if (!empty($search) && !empty($options['searchFields'])) {
                $searchTerm = [];
                foreach ($options['searchFields'] as $field) {
                    $searchTerm[] = "LOWER(\"{$field}\") LIKE LOWER('%{$search}%')";
                }
                $searchCond = implode(' OR ', $searchTerm);
                $sql = "SELECT * FROM ( {$sql} ) st WHERE {$searchCond}";
            }
            // debug($sql, 1, 1);
            
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
                    $sql = "SELECT * FROM ({$sql}) x ORDER BY \"{$sidx}\" {$sord} LIMIT {$limit} OFFSET {$offset} ";
                } else {
                    $sql = "SELECT * FROM ({$sql}) x LIMIT {$limit} OFFSET {$offset} ";
                }
                
            }
            

            // debug($sql, 1, 1);
            $rows = DB::select($sql);

            // $this->page = $page;
            $this->perpage = $limit;
            $this->totalpages = ceil($totalrecords / $limit);
            $this->totalrecords = $totalrecords;
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
            // "page": 1,
        // "pages": 1,
        // "perpage": -1,
        // "total": 40,
        // "sort": "asc",
        // "field": "RecordID"
                // 'meta' => [
                // 'page' => $this->page,
                // 'pages' => $this->totalpages,
                'iTotalDisplayRecords' => $this->totalrecords,
                'sEcho' => request()->input('sEcho') ?? 0,
                'draw' => request()->input('draw') ?? 0,
                'iTotalRecords' => $this->totalrecords,
                // 'links' => [
                //     'previous' => $this->previouspage,
                //     'current' => $this->currentpage,
                //     'next' => $this->nextpage,
                // ]
                // ],
                'data' => $this->rows
            ];
        } else {
            return response($this->message)->header('Content-Type', 'text/plain');
        }
    }
    
}
