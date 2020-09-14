<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\jqGrid;
use App\Libraries\DataTable;
use PDF;
use DB;

class SummaryController extends Controller
{
    public function index()
    {
        $valperiode = currentUser('PeriodeSensus');
        if (in_array(currentUser('RoleID'), [2,3,6])) {
            $valperiode = null;
        }
        $periode = \DB::table('PeriodeSensus')->where('IsOpen','Y')->orderBy('Tahun', 'DESC')->get();
        $tingkatwilayah = \DB::table('TingkatWilayah')->where('ID','>=',currentUser('TingkatWilayahID'))->orderBy('ID', 'ASC')->get();
        return view('laporan.summary_data')->with(compact('periode', 'valperiode', 'tingkatwilayah'));;
    }
    
    public function data_bak()
    {
        
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        $periode = request()->input('PeriodeSensus');
        switch (request()->input('groupby')) {
            case 4:
                $sql = '
                WITH t1 AS (
                    SELECT DISTINCT id_desa, status_sensus, count(*) qty FROM mst_formulir WHERE id_desa IN ('.$whereKel.')
                    AND  COALESCE(status_sensus,\'\') <> \'\'
                    GROUP BY id_desa, status_sensus
                )
                , t2 AS (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'StatusSensus\'
                )
                , w As (
                    SELECT DISTINCT "KelurahanID" as id_kelurahan, "NamaKelurahan" as nama_kelurahan FROM "RT" WHERE "KelurahanID" IN ('.$whereKel.')
                )
                
                SELECT DISTINCT w.id_kelurahan, w.nama_kelurahan, t2."Code" AS status_sensus, t2."Value" as status_nama, COALESCE(t3.qty, 0) qty 
                FROM t1 
                CROSS JOIN t2
                LEFT JOIN t1 AS t3 ON CAST(t3.status_sensus AS VARCHAR)=CAST(t2."Code" AS VARCHAR) AND t1.id_desa=t3.id_desa 
                LEFT JOIN  w ON w."id_kelurahan"=t1.id_desa
                ORDER BY w.id_kelurahan, t2."Code" ';
                                
                $sqlagg = 'SELECT id_kelurahan, nama_kelurahan, array_agg(status_sensus) as status_sensus, array_agg(status_nama) as status_nama, array_agg(quote_literal(status_sensus)||\':\'||qty) as qty 
                FROM ('.$sql.') x GROUP BY id_kelurahan, nama_kelurahan';
                break;
            case 5:
                $sql = '
                WITH t1 AS (
                    SELECT id_desa, id_rw, status_sensus, count(*) qty FROM mst_formulir WHERE id_desa IN ('.$whereKel.')
                    AND  COALESCE(status_sensus,\'\') <> \'\'
                    GROUP BY id_desa, id_rw, status_sensus
                )
                , t2 AS (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'StatusSensus\'
                )
                , w As (
                    SELECT 
                    DISTINCT "KelurahanID" as id_kelurahan, "NamaKelurahan" as nama_kelurahan, id_rw, "NamaRW" as nama_rw FROM "RT" WHERE "KelurahanID" IN ('.$whereKel.')
                )
                
                SELECT DISTINCT w.id_kelurahan, w.nama_kelurahan, w.id_rw, w.nama_rw, t2."Code" AS status_sensus, t2."Value" as status_nama, COALESCE(t3.qty, 0) qty 
                FROM t1 
                CROSS JOIN t2
                LEFT JOIN t1 AS t3 ON CAST(t3.status_sensus AS VARCHAR)=CAST(t2."Code" AS VARCHAR) AND t1.id_desa=t3.id_desa AND t1.id_rw=t3.id_rw
                LEFT JOIN w ON w.id_rw=t1.id_rw
                ORDER BY w.id_kelurahan, w.id_rw, t2."Code" ';
                                
                $sqlagg = 'SELECT id_kelurahan, nama_kelurahan, id_rw, nama_rw, array_agg(status_sensus) as status_sensus, array_agg(status_nama) as status_nama, array_agg(quote_literal(status_sensus)||\':\'||qty) as qty 
                FROM ('.$sql.') x GROUP BY id_kelurahan, nama_kelurahan, id_rw, nama_rw';
                break;
            case 6:
                $sql = '
                WITH t1 AS (
                    SELECT id_desa, id_rw, id_rt, status_sensus, count(*) qty FROM mst_formulir WHERE id_desa IN ('.$whereKel.')
                    AND  COALESCE(status_sensus,\'\') <> \'\'
                    GROUP BY id_desa, id_rw, id_rt, status_sensus
                )
                , t2 AS (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'StatusSensus\'
                )
                , w As (
                    SELECT 
                    DISTINCT "KelurahanID" as id_kelurahan, "NamaKelurahan" as nama_kelurahan, id_rw, "NamaRW" as nama_rw, id_rt, nama_rt FROM "RT" WHERE "KelurahanID" IN ('.$whereKel.')
                )
                
                SELECT DISTINCT w.id_kelurahan, w.nama_kelurahan, w.id_rw, w.nama_rw, w.id_rt, w.nama_rt, t2."Code" AS status_sensus, t2."Value" as status_nama, COALESCE(t3.qty, 0) qty 
                FROM t1 
                CROSS JOIN t2
                LEFT JOIN t1 AS t3 ON CAST(t3.status_sensus AS VARCHAR)=CAST(t2."Code" AS VARCHAR) AND t1.id_desa=t3.id_desa AND t1.id_rw=t3.id_rw AND t1.id_rt=t3.id_rt
                LEFT JOIN w ON w.id_rt=t1.id_rt
                ORDER BY w.id_kelurahan, w.id_rw, w.id_rt, t2."Code" ';
                                
                $sqlagg = 'SELECT id_kelurahan, nama_kelurahan, id_rw, nama_rw, id_rt, nama_rt, array_agg(status_sensus) as status_sensus, array_agg(status_nama) as status_nama, array_agg(quote_literal(status_sensus)||\':\'||qty) as qty 
                FROM ('.$sql.') x GROUP BY id_kelurahan, nama_kelurahan, id_rw, nama_rw, id_rt, nama_rt';
                break;
                
        }
        
        if (request()->input('print')==1) {
            if (!empty(request()->input('RT'))) {
                $wilrow = DB::table('v_rt')->where('id_rt', request()->input('RT'))->first();
            } elseif (!empty(request()->input('RW'))) {
                $wilrow = DB::table('v_rw')->where('id_rw', request()->input('RW'))->first();
            } elseif (!empty(request()->input('Kelurahan'))) {
                $wilrow = DB::table('v_kelurahan')->where('id_kelurahan', request()->input('Kelurahan'))->first();
            } else {
                $wilrow = collect(currentUser('AksesWilayah'))->first();
            }

            $rows = \DB::select($sqlagg);
            // foreach ($rows as $row) {
                // $status = str_replace('\'', '"', $row->qty);
                // $status = json_decode($status, true);
            // }
            // return view('laporan.summary_pdf',['rows'=>$rows, 'periode'=>$periode, 'nama_wilayah'=>$nama_wilayah]);
            $pdf = PDF::loadview('laporan.summary_pdf', compact('rows', 'periode', 'wilrow'));
            return $pdf->stream();
        } else {
            $data = new jqGrid($sql);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }

    public function data()
    {
        
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        $periode = request()->input('PeriodeSensus');
        $sql = '';
        

        $sql = 'select v.id_provinsi, v.id_kabupaten,  v.id_kecamatan, v.id_kelurahan,  v.id_rw, v.id_rt 
                ,v.nama_provinsi, upper(v.nama_kabupaten) as nama_kabupaten, v.nama_kecamatan, v.nama_kelurahan, v.nama_rw, v.nama_rt
                , a.jml_valid
                , b.jml_notvalid
                , c.jml_anomali
                , d.jml_anulir
                , e.jml_received
                from v_rt v
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_valid from v_data_valid group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) a on a.id_desa = v.id_kelurahan and a.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_notvalid from v_data_notvalid group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) b on b.id_desa = v.id_kelurahan and b.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_anomali from v_data_anomali group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) c on c.id_desa = v.id_kelurahan and c.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_anulir from v_data_anulir group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) d on d.id_desa = v.id_kelurahan and d.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_received from v_data_received group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) e on e.id_desa = v.id_kelurahan and e.id_rt = v.id_rt
                where id_kelurahan IN ('.$whereKel.')
                order by v.id_rt
                limit 100';


        switch (request()->input('groupby')) {
            case 3:   // group kecamatan.          
                $sql = 'select x.id_kecamatan, x.nama_kecamatan , null nama_kelurahan, null nama_rt, null nama_rw
                        , sum(x.jml_valid) as jml_valid
                        , sum(x.jml_notvalid) as jml_notvalid
                        , sum(x.jml_anomali) as jml_anomali
                        , sum(x.jml_anulir) as jml_anulir
                        , sum(x.jml_received) as jml_received
                        from ('.$sql.') x
                        group by x.id_kecamatan, x.nama_kecamatan';
                break;
            case 4:      
                $sql = 'select x.id_kecamatan, x.nama_kecamatan, x.id_kelurahan, x.nama_kelurahan, null nama_rt, null nama_rw
                        , sum(x.jml_valid) as jml_valid
                        , sum(x.jml_notvalid) as jml_notvalid
                        , sum(x.jml_anomali) as jml_anomali
                        , sum(x.jml_anulir) as jml_anulir
                        , sum(x.jml_received) as jml_received
                        from ('.$sql.') x
                        group by x.id_kecamatan, x.nama_kecamatan, x.id_kelurahan, x.nama_kelurahan';                                          
                break;
            case 5:
                $sql = 'select x.id_kecamatan, x.nama_kecamatan, x.id_kelurahan, x.nama_kelurahan, x.id_rw, x.nama_rw , null nama_rt        
                        , sum(x.jml_valid) as jml_valid
                        , sum(x.jml_notvalid) as jml_notvalid
                        , sum(x.jml_anomali) as jml_anomali
                        , sum(x.jml_anulir) as jml_anulir
                        , sum(x.jml_received) as jml_received
                        from ('.$sql.') x
                        group by x.id_kecamatan, x.nama_kecamatan, x.id_kelurahan, x.nama_kelurahan, x.id_rw, x.nama_rw ';  
                break;
            case 6:
                $sql = 'select x.id_kecamatan, x.nama_kecamatan, x.id_kelurahan, x.nama_kelurahan, x.id_rw, x.nama_rw 
                        , x.id_rt, x.nama_rt 
                        , sum(x.jml_valid) as jml_valid
                        , sum(x.jml_notvalid) as jml_notvalid
                        , sum(x.jml_anomali) as jml_anomali
                        , sum(x.jml_anulir) as jml_anulir
                        , sum(x.jml_received) as jml_received
                        from ('.$sql.') x
                        group by x.id_kecamatan, x.nama_kecamatan, x.id_kelurahan, x.nama_kelurahan, x.id_rw, x.nama_rw, x.id_rt, x.nama_rt';  
                break;
                
        }
        
        if (request()->input('print')==1) {
            if (!empty(request()->input('RT'))) {
                $wilrow = DB::table('v_rt')->where('id_rt', request()->input('RT'))->first();
            } elseif (!empty(request()->input('RW'))) {
                $wilrow = DB::table('v_rw')->where('id_rw', request()->input('RW'))->first();
            } elseif (!empty(request()->input('Kelurahan'))) {
                $wilrow = DB::table('v_kelurahan')->where('id_kelurahan', request()->input('Kelurahan'))->first();
            } else {
                $wilrow = collect(currentUser('AksesWilayah'))->first();
            }

            $rows = \DB::select($sql);
        
            $pdf = PDF::loadview('laporan.summary_pdf', compact('rows', 'periode', 'wilrow'));
            return $pdf->stream();
        } else {
            $data = new DataTable($sql, ['searchFields'=>['id_kecamatan', 'nama_kecamatan']]);
            $result = $data->get();
            return $this->jsonOutput($result);

        }
    }

     public function cekData()
    {
        
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        $periode = request()->input('PeriodeSensus');
        $sql = '';
       
        $sql = 'select v.id_provinsi, v.id_kabupaten,  v.id_kecamatan, v.id_kelurahan,  v.id_rw, v.id_rt 
                ,v.nama_provinsi, upper(v.nama_kabupaten) as nama_kabupaten, v.nama_kecamatan, v.nama_kelurahan, v.nama_rw, v.nama_rt
                , a.jml_valid
                , b.jml_notvalid
                , c.jml_anomali
                , d.jml_anulir
                , e.jml_received
                from v_rt v
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_valid from v_data_valid group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) a on a.id_desa = v.id_kelurahan and a.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_notvalid from v_data_notvalid group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) b on b.id_desa = v.id_kelurahan and b.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_anomali from v_data_anomali group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) c on c.id_desa = v.id_kelurahan and c.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_anulir from v_data_anulir group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) d on d.id_desa = v.id_kelurahan and d.id_rt = v.id_rt
                left join (select distinct id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt, sum(cnt) as jml_received from v_data_received group by id_propinsi, id_kabupaten, id_kecamatan, id_desa, id_rw, id_rt) e on e.id_desa = v.id_kelurahan and e.id_rt = v.id_rt
                where id_kelurahan IN ('.$whereKel.')
                order by v.id_rt
                limit 100';

            //var_dump($sqlagg); exit;

            if ($sql != '') {
                $rows = \DB::select($sql);

                if ($rows == null) { 
                    $result = ['status' => false,'message' => 'Data Tidak Ditemukan!']; 
                } else {
                    $result = ['status' => true,'message' => '']; 
                }                 
            } else {
                $result = ['status' => false,'message' => 'Data Tidak Ditemukan!']; 
            }

            return $result;      
    }   

    public function cekData_bak()
    {
        
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        $periode = request()->input('PeriodeSensus');
        $sqlagg = '';
        switch (request()->input('groupby')) {
            case 3:

            case 4:
                $sql = '
                WITH t1 AS (
                    SELECT DISTINCT id_desa, status_sensus, count(*) qty FROM mst_formulir WHERE id_desa IN ('.$whereKel.')
                    AND  COALESCE(status_sensus,\'\') <> \'\'
                    GROUP BY id_desa, status_sensus
                )
                , t2 AS (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'StatusSensus\'
                )
                , w As (
                    SELECT DISTINCT "KelurahanID" as id_kelurahan, "NamaKelurahan" as nama_kelurahan FROM "RT" WHERE "KelurahanID" IN ('.$whereKel.')
                )
                
                SELECT DISTINCT w.id_kelurahan, w.nama_kelurahan, t2."Code" AS status_sensus, t2."Value" as status_nama, COALESCE(t3.qty, 0) qty 
                FROM t1 
                CROSS JOIN t2
                LEFT JOIN t1 AS t3 ON CAST(t3.status_sensus AS VARCHAR)=CAST(t2."Code" AS VARCHAR) AND t1.id_desa=t3.id_desa 
                LEFT JOIN  w ON w."id_kelurahan"=t1.id_desa
                ORDER BY w.id_kelurahan, t2."Code" ';
                                
                $sqlagg = 'SELECT id_kelurahan, nama_kelurahan, array_agg(status_sensus) as status_sensus, array_agg(status_nama) as status_nama, array_agg(quote_literal(status_sensus)||\':\'||qty) as qty 
                FROM ('.$sql.') x GROUP BY id_kelurahan, nama_kelurahan';
                //break;
            case 5:
                $sql = '
                WITH t1 AS (
                    SELECT id_desa, id_rw, status_sensus, count(*) qty FROM mst_formulir WHERE id_desa IN ('.$whereKel.')
                    AND  COALESCE(status_sensus,\'\') <> \'\'
                    GROUP BY id_desa, id_rw, status_sensus
                )
                , t2 AS (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'StatusSensus\'
                )
                , w As (
                    SELECT 
                    DISTINCT "KelurahanID" as id_kelurahan, "NamaKelurahan" as nama_kelurahan, id_rw, "NamaRW" as nama_rw FROM "RT" WHERE "KelurahanID" IN ('.$whereKel.')
                )
                
                SELECT DISTINCT w.id_kelurahan, w.nama_kelurahan, w.id_rw, w.nama_rw, t2."Code" AS status_sensus, t2."Value" as status_nama, COALESCE(t3.qty, 0) qty 
                FROM t1 
                CROSS JOIN t2
                LEFT JOIN t1 AS t3 ON CAST(t3.status_sensus AS VARCHAR)=CAST(t2."Code" AS VARCHAR) AND t1.id_desa=t3.id_desa AND t1.id_rw=t3.id_rw
                LEFT JOIN w ON w.id_rw=t1.id_rw
                ORDER BY w.id_kelurahan, w.id_rw, t2."Code" ';
                                
                $sqlagg = 'SELECT id_kelurahan, nama_kelurahan, id_rw, nama_rw, array_agg(status_sensus) as status_sensus, array_agg(status_nama) as status_nama, array_agg(quote_literal(status_sensus)||\':\'||qty) as qty 
                FROM ('.$sql.') x GROUP BY id_kelurahan, nama_kelurahan, id_rw, nama_rw';
                //break;
            case 6:
                $sql = '
                WITH t1 AS (
                    SELECT id_desa, id_rw, id_rt, status_sensus, count(*) qty FROM mst_formulir WHERE id_desa IN ('.$whereKel.')
                    AND  COALESCE(status_sensus,\'\') <> \'\'
                    GROUP BY id_desa, id_rw, id_rt, status_sensus
                )
                , t2 AS (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'StatusSensus\'
                )
                , w As (
                    SELECT 
                    DISTINCT "KelurahanID" as id_kelurahan, "NamaKelurahan" as nama_kelurahan, id_rw, "NamaRW" as nama_rw, id_rt, nama_rt FROM "RT" WHERE "KelurahanID" IN ('.$whereKel.')
                )
                
                SELECT DISTINCT w.id_kelurahan, w.nama_kelurahan, w.id_rw, w.nama_rw, w.id_rt, w.nama_rt, t2."Code" AS status_sensus, t2."Value" as status_nama, COALESCE(t3.qty, 0) qty 
                FROM t1 
                CROSS JOIN t2
                LEFT JOIN t1 AS t3 ON CAST(t3.status_sensus AS VARCHAR)=CAST(t2."Code" AS VARCHAR) AND t1.id_desa=t3.id_desa AND t1.id_rw=t3.id_rw AND t1.id_rt=t3.id_rt
                LEFT JOIN w ON w.id_rt=t1.id_rt
                ORDER BY w.id_kelurahan, w.id_rw, w.id_rt, t2."Code" ';
                                
                $sqlagg = 'SELECT id_kelurahan, nama_kelurahan, id_rw, nama_rw, id_rt, nama_rt, array_agg(status_sensus) as status_sensus, array_agg(status_nama) as status_nama, array_agg(quote_literal(status_sensus)||\':\'||qty) as qty 
                FROM ('.$sql.') x GROUP BY id_kelurahan, nama_kelurahan, id_rw, nama_rw, id_rt, nama_rt';
                //break;
                
        }
        
            //var_dump($sqlagg); exit;

            if ($sqlagg != '') {
                $rows = \DB::select($sqlagg);

                //var_dump($rows); exit;

                //if ($rows) { 
                if ($rows == null) { 
                    $result = ['status' => false,'message' => 'Data Tidak Ditemukan!']; 
                } else {
                    $result = ['status' => true,'message' => '']; 
                }                 
            } else {
                $result = ['status' => false,'message' => 'Data Tidak Ditemukan!']; 
            }

 

            return $result;      
    }
    
}
