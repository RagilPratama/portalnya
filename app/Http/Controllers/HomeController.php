<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\DataTable;
use App\Libraries\jqGrid;

use App\Models\Sensus;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }
    
    public function home()
    {

        $model = new User();
        $reminder = $model->getReminder();
        return view('home', ['reminder' => $reminder ]);
    }
    
    
    public function statussensus()
    {
        $sql = 'SELECT  COALESCE(y."Value", \'Lainnya\') as nama_status, sum(cnt) as jumlah
                FROM (
                SELECT CASE WHEN status_sensus IN (\'1\',\'2\',\'3\',\'4\') THEN status_sensus
                ELSE \'0\'
                END AS status, count(*) cnt 
                FROM mst_formulir a
                GROUP BY status_sensus
                ) x 
                LEFT JOIN "Parameter" y ON y."Code"=x.status AND y."Group"=\'StatusSensus\'
                GROUP BY x.status, y."Value"
                order by status';


        $rows = \DB::select($sql);
        $labels = [];
        $data = [];
        $backgroundColor = ['#b2d4f5', '#fcc3a7', '#8fd1bb', '#f8b4c9', '#d3bdeb', '#83d1da', '#99a0f9', '#e597d2', '#d1d9dc', '#fccaca', '#85a1bb'];
        $totaldata = 0;
        foreach ($rows as $row) {
            $labels[] = $row->nama_status;
            $data[] = $row->jumlah;
            $totaldata += $row->jumlah;
            // $backgroundColor[] = $row->jumlah;
        };
        $chartdata = [
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => $backgroundColor,
                    'data' => $data
                ]
            ],
            'customdata' => ['totaldata'=>$totaldata]
        ];
        return $this->jsonOutput($chartdata);
    }
    
    public function datasensus()
    {
        $Wilayahid = currentUser('TingkatWilayahID');
        $Id = currentUser('ID');
        $whereIn = null;


        if ($Wilayahid != '') {
            $model = new \App\Models\Master\Kelurahan();
            $wilayah = $model->getByUserID();
            $whereIn = ' where a.id_desa in ('.implode(',', $wilayah->pluck('id')->toArray()).')';            
        }
        
        // $msensus = new Sensus();
        // $rows = $msensus->where('periode_sensus','2020')->orderBy('create_date', 'DESC')->take(10)->get();
        $sql = 'select a.*, b."NamaProvinsi", c."Value" as "StatusSensus"
                FROM mst_formulir a
                LEFT JOIN "RT" b  ON b.id_rt=a.id_rt
                LEFT JOIN "Parameter" c ON c."Group"=\'StatusSensus\' AND c."Code"=a.status_sensus '
                .$whereIn.' 
                ORDER BY create_date DESC 
                LIMIT 10';

        //debug($sql); exit;

        $rows = \DB::select($sql);
        $result = [];
        foreach ($rows as $row) {
            $item = [];
            $item['alamat'] = $row->alamat1;
            $item['wilayah'] = $row->NamaProvinsi;
            $item['jml_jiwa'] = $row->jml_jiwa;
            $item['create_date'] = $row->create_date;
            $item['status_sensus'] = $row->StatusSensus;
            $item['status_sensus_id'] = $row->status_sensus;
            $result[] = $item;
        }
        return $this->jsonOutput($result);
        
    }
    
    public function dailysumdata()
    {

        $Wilayahid = currentUser('TingkatWilayahID');
        $Id = currentUser('ID');
        $whereIn  = null;
        $_whereIn = null;

        if ($Wilayahid != '') {
            $model = new \App\Models\Master\Kelurahan();
            $wilayah = $model->getByUserID();
            $whereIn = ' and id_desa in ('.implode(',', $wilayah->pluck('id')->toArray()).')';            
        }   

        $sql = 'select * FROM (
                select create_date, count(*) cnt FROM mst_formulir
                WHERE create_date is not null '.$whereIn.' 
                GROUP BY create_date
                ORDER BY create_date desc
                LIMIT 15
                ) x ORDER BY create_date desc';

        $rows = \DB::select($sql);
        $labels = [];
        $data = [];
        foreach ($rows as $row) {
            $labels[] = date_format(date_create($row->create_date), 'd/m');
            $data[] = $row->cnt;
        };
        $chartdata = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data
                ]
            ]
        ];
        return $this->jsonOutput($chartdata);
    }


    public function dailysumdataTable()
    {
        
        $Wilayahid = currentUser('TingkatWilayahID');
        $Id = currentUser('ID');
        $whereIn  = null;
        $_whereIn = null;


        if ($Wilayahid != '') {
            $model = new \App\Models\Master\Kelurahan();
            $wilayah = $model->getByUserID();
            $whereIn = ' and id_desa in ('.implode(',', $wilayah->pluck('id')->toArray()).')';            
        }    
               
        $sql = 'select to_char(create_date,\'dd-mm-yy\') as create_date, cnt FROM (
        select create_date, count(*) cnt FROM mst_formulir
        WHERE create_date is not null '.$whereIn.' 
        GROUP BY create_date
        ORDER BY create_date desc
        LIMIT 15
        ) x ORDER BY to_char(create_date,\'mm\') desc, create_date desc';

        //debug($sql); exit;

        $rows = \DB::select($sql);
        $result = [];
        foreach ($rows as $row) {
            $item = [];
            $item['create_date'] = $row->create_date;
            $item['cnt'] = $row->cnt;            
            $result[] = $item;
        }
        return $this->jsonOutput($result);
        
    }
    

    public function chart1 ()
    {

        $Wilayahid = currentUser('TingkatWilayahID');
        $Id = currentUser('ID');
        $whereIn  = null;
        $_whereIn = null;
        $prosen = 0;

        if ($Wilayahid != '') {
            $model = new \App\Models\Master\Kelurahan();
            $wilayah = $model->getByUserID();
            $whereIn = ' where id_kelurahan in ('.implode(',', $wilayah->pluck('id')->toArray()).')';            
            $_whereIn = ' and id_desa in ('.implode(',', $wilayah->pluck('id')->toArray()).')';                    
        }


        $sql = ' select * FROM (
                    select create_date, count(*) cnt FROM mst_formulir
                    WHERE create_date is not null
                      and status_sensus = \'1\' '.$_whereIn.'
                     GROUP BY create_date
                     ORDER BY create_date desc
                    LIMIT 5
                    ) x ORDER BY create_date';

        $target = 'select coalesce(sum("Target_KK"),0) jml_target_kk,  round((coalesce(sum("Target_KK"),0)  / 30 )) as rata 
                    from "Target_KK" where "ID_RT" in (
                    select distinct id_rt from v_data_wilayah_all
                    '.$whereIn.' )';

        //debug($target); exit;            

        $terdata = 'select count(*) terdata FROM mst_formulir
                    WHERE create_date is not null
                      and status_sensus = \'1\' '.$_whereIn;            
        
        //$max  = 'select max(x.cnt) from ('.$sql.') x';

        //$maxx = \DB::select($max);
        
        $dataTarget = \DB::select($target);
        $dataTerdata = \DB::select($terdata);
        
        $rows = \DB::select($sql);
        $labels = [];
        $data = [];
        $data2 = [];

        $rata2 = $dataTarget[0]->rata;
        
        foreach ($rows as $row) {
            $labels[] = date_format(date_create($row->create_date), 'j M');
            $data[] = $row->cnt;
            $data2[] = $rata2;
        };
       
        if ($dataTarget[0]->jml_target_kk == 0) {
            $prosen = 0;
        } else {
            $prosen = round( ( $dataTerdata[0]->terdata / $dataTarget[0]->jml_target_kk ) * 100 , 0);    
            if ($prosen > 100) {
                $prosen = 100;
            }
        }
        
        $step = 10;
        //$value     = (float) $maxx[0]->max;
        $valuemax  = 0;
        if (count($rows) > 0) {
            $valuemax = max($data);
        }

        if (in_array($valuemax, range(1, 50))) {
            $step     = 10;
            $valuemax = (float)$valuemax;
        } elseif (in_array($valuemax, range(51, 100))) {
            # code...
             $step = 20;
             $valuemax = (float)$valuemax;
        } elseif (in_array($valuemax, range(101, 500))) {
            # code...
             $step     = 100;
             $valuemax = (float)$valuemax;
        } else {
             $step = 500;
             $valuemax = (float)$valuemax;
        }  

        if ($valuemax < 10) {
            $valuemax = 10; 
        } elseif ($valuemax < 20) {
            $valuemax = 20; 
        }

        //debug($prosen); exit;

        if ($valuemax < $rata2) {
            $valuemax = $rata2 + $step ; 
        } 

        $chartdata = [
            'labels' => $labels,
            'data1' => $data,
            'data2' => $data2,
            'max' => $valuemax,
            'target' => $dataTarget[0]->jml_target_kk,
            'terdata' => $dataTerdata[0]->terdata,
            'prosen' => (string)$prosen.'%',
            'stepSize' => $step,
            // 'datasets' => [
            //     [
            //         //'backgroundColor' => '#5d78ff',
            //         'data' => $data                    
            //     ]
            // ]
        ];
        return $this->jsonOutput($chartdata);
    }


    public function chart2 ()
    {

        $Wilayahid = currentUser('TingkatWilayahID');
        $Id = currentUser('ID');
        $whereIn = null;
        $totalanomali = 0;



        if ($Wilayahid != '') {
            $model = new \App\Models\Master\Kelurahan();
            $wilayah = $model->getByUserID();
            $whereIn = ' where id_desa in ('.implode(',', $wilayah->pluck('id')->toArray()).')';                       
            $sqldata = 'select a.*, coalesce(b.jml_valid,0) as jml_valid, coalesce(c.jml_notvalid,0) as jml_notvalid, coalesce(d.jml_anomali,0) as jml_anomali, coalesce(e.jml_anulir,0) as jml_anulir from (SELECT i::date AS create_date
                        FROM generate_series((now() - \'5 days\'::interval), now(), \'1 day\'::interval) i) a
                        LEFT JOIN (select create_date, sum(cnt) as jml_valid  from v_data_valid '.$whereIn.' group by create_date ORDER BY 1 DESC) b on a.create_date = b.create_date
                        LEFT JOIN (select create_date, sum(cnt) as jml_notvalid from v_data_notvalid '.$whereIn.' group by create_date ORDER BY 1 DESC) c on a.create_date = c.create_date
                        LEFT JOIN (select create_date, sum(cnt) as jml_anomali from v_data_anomali '.$whereIn.' group by create_date ORDER BY 1 DESC) d on a.create_date = d.create_date
                         LEFT JOIN (select create_date, sum(cnt) as jml_anulir from v_data_anulir '.$whereIn.' group by create_date ORDER BY 1 DESC)e on a.create_date = e.create_date
                        order by 1 desc';         

        } else {
            $sqldata = 'select a.*, coalesce(b.jml_valid,0) as jml_valid, coalesce(c.jml_notvalid,0) as jml_notvalid, coalesce(d.jml_anomali,0) as jml_anomali, coalesce(e.jml_anulir,0) as jml_anulir from (SELECT i::date AS create_date
                        FROM generate_series((now() - \'10 days\'::interval), now(), \'1 day\'::interval) i) a
                        LEFT JOIN (select create_date, sum(cnt) as jml_valid  from v_data_valid '.$whereIn.' group by create_date ORDER BY 1 DESC) b on a.create_date = b.create_date
                        LEFT JOIN (select create_date, sum(cnt) as jml_notvalid from v_data_notvalid '.$whereIn.' group by create_date ORDER BY 1 DESC) c on a.create_date = c.create_date
                        LEFT JOIN (select create_date, sum(cnt) as jml_anomali from v_data_anomali '.$whereIn.' group by create_date ORDER BY 1 DESC) d on a.create_date = d.create_date
                         LEFT JOIN (select create_date, sum(cnt) as jml_anulir from v_data_anulir '.$whereIn.' group by create_date ORDER BY 1 DESC)e on a.create_date = e.create_date
                        order by 1 desc limit 10';
        }

        $datavalid    = 'select COALESCE(sum(cnt),0) as jml_valid from v_data_valid '.$whereIn;          
        $dataNotvalid = 'select COALESCE(sum(cnt),0) as jml_notvalid from v_data_notvalid '.$whereIn;          
        $dataAnomali  = 'select COALESCE(sum(cnt),0) as jml_anomali from v_data_anomali '.$whereIn;          
        $dataAnulir   = 'select COALESCE(sum(cnt),0) as jml_anulir  from v_data_anulir '.$whereIn;          

        //$sql = 'select * from v_dashboard_chart2'.$whereIn; 

        //debug($sqldata); exit;         

        //$max  = 'select max(x.jml_valid) from ('.$sqldata.') x';

        //$maxx     = \DB::select($max);
        $valid    = \DB::select($datavalid);
        $notvalid = \DB::select($dataNotvalid);
        $anomali  = \DB::select($dataAnomali);
        $anulir   = \DB::select($dataAnulir);
          

        $rows = \DB::select($sqldata);
       //$rowx = \DB::select($data);

        $labels = [];
        $data = [];
        $data2 = [];
        $data3 = [];
        $data4 = [];
        $dataall = [];
       
       $chartdata = ['labels' => null,
                'data1' => null,
                'data2' => null,
                'data3' => null,
                'data4' => null,
                'max' => 0,
                'jml_valid' =>  0,
                'jml_notvalid' =>  0,
                'jml_anomali' =>  0,
                'jml_anulir' => 0,
                'totalanomali' =>  0,
                'stepSize' => 10,
            ];

        //debug(count($rows)); exit; 
            
        if (count($rows) > 0) {

            $step = 10;
            //$value     = (float) $maxx[0]->max;
            $valuemax  = 0;
            

            foreach ($rows as $row) {
                $labels[] = date_format(date_create($row->create_date), 'j M');
                $data[] = $row->jml_valid;
                $data2[] = $row->jml_notvalid;
                $data3[] = $row->jml_anomali;
                $data4[] = $row->jml_anulir;
            };

            $valuemax = max(array_merge($data, $data2, $data3, $data4));
            if (in_array($valuemax, range(1, 50))) {
                $step     = 10;
                $valuemax = (float)$valuemax;
            } elseif (in_array($valuemax, range(51, 100))) {
                # code...
                 $step = 20;
                 $valuemax = (float)$valuemax + $step;
            } elseif (in_array($valuemax, range(101, 500))) {
                # code...
                 $step     = 100;
                 $valuemax = (float)$valuemax + $step;
            } else {
                 $step = 500;
                 $valuemax = (float)$valuemax + $step;
            }  

            if ($valuemax < 10) {
                $valuemax = 10; 
            } elseif ($valuemax < 20) {
                $valuemax = 20; 
            } else {
                $valuemax = (float)$valuemax + $step;
            }

            //debug($valuemax); exit;

            $totalanomali = (float)$anulir[0]->jml_anulir + (float)$notvalid[0]->jml_notvalid + (float) $anomali[0]->jml_anomali;

            $chartdata = [
                'labels' => $labels,
                'data1' => $data,
                'data2' => $data2,
                'data3' => $data3,
                'data4' => $data4,
                'max' => $valuemax,
                'jml_valid' =>  $valid[0]->jml_valid,
                'jml_notvalid' =>  $notvalid[0]->jml_notvalid,
                'jml_anomali' =>  $anomali[0]->jml_anomali,
                'jml_anulir' =>  $anulir[0]->jml_anulir,
                'totalanomali' =>  $totalanomali,
                'stepSize' => $step,
            ];
        } 
        
        return $this->jsonOutput($chartdata);
    }

    public function statPendata()
    {
        $m = new \App\Models\User();
        if (auth()->check()) {
            $sql = $m->getStatPendata();
            $rows = \DB::select($sql);
            $result['total'] = collect($rows)->sum('total');
            $result['data'] = $rows;
        } else {
            $rows = $m->select('TingkatWilayahID', \DB::raw('count(*) as total'))
                    ->where('RoleID',5)
                    ->groupBy('TingkatWilayahID')
                    ->get();
            $result['total'] = $rows->sum('total');
            $result['data'] = $rows->toArray();
        }
        return $this->jsonOutput($result);
    }
}
