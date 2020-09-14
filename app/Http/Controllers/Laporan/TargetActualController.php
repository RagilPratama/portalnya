<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAkses;
use App\Libraries\jqGrid;
use App\Libraries\DataTable;
use PDF;
use DB;

class TargetActualController extends Controller
{
    public function index()
    {
        $userID = currentUser('ID');
        $periode = \DB::table('PeriodeSensus')->where('IsOpen','Y')->orderBy('Tahun', 'DESC')->get();
        $valperiode = currentUser('PeriodeSensus');
        $userAkses = UserAkses::where('UserID', $userID)->get()->pluck('WilayahID')->toArray();
        $valwilayah = count($userAkses)==1 ? $userAkses[0] : null;
        if (in_array(currentUser('RoleID'), [2,3,6])) { // check RoleID
            $valperiode = null;
            $valwilayah = null;
        }
        
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        return view('laporan.targetactual')->with(compact('periode', 'valperiode', 'wilayah', 'valwilayah'));
    }

    public function data()
    {
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $periode = request()->input('PeriodeSensus');
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        $sql = 'SELECT a.id_rt
        , a.nama_rt
        , a.id_rw
        , a."KelurahanID" id_kelurahan
        , a."NamaRW" nama_rw
        , a."NamaKelurahan" nama_kelurahan
        , COALESCE(b."Target_KK", 0) "TargetKK"
        , COALESCE(f.actual, 0) "Actual"
        , f.create_by "UserName"
        ,CASE WHEN COALESCE(b."Target_KK", 0)>0 THEN 
            ROUND(
                100
                *
                CAST(COALESCE(f.actual, 0) AS DECIMAL)
                /
                CAST(COALESCE(b."Target_KK", 0) AS DECIMAL)
                , 2
            )
            ELSE 0
        END 
        AS  "Persen"
        FROM "RT" a
        LEFT JOIN "Target_KK" b ON b."ID_RT"=a.id_rt
        LEFT JOIN (
            SELECT a.periode_sensus
                , a.create_by
                , a.id_desa
                , a.id_rw
                , a.id_rt
                , count(*) as actual
                FROM "mst_formulir" a
                WHERE status_sensus=\'1\' AND periode_sensus=\''.$periode.'\' 
                GROUP BY periode_sensus, create_by, id_desa, id_rw, id_rt
        ) f ON a.id_rt=f.id_rt
        WHERE a."KelurahanID" IN ('.$whereKel.')
        
        ';
        if (!empty(request()->input('RT'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
        } elseif (!empty(request()->input('RW'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
        } elseif (!empty(request()->input('Kelurahan'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
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
            $rows = DB::select($sql);
            $pdf = PDF::loadview('laporan.target_pdf', compact('rows', 'periode', 'wilrow'));
            return $pdf->stream('MonTargetTerdata.pdf');
        } else {
            $data = new DataTable($sql, ['searchFields'=>['UserName', 'nama_kelurahan', 'nama_rw', 'nama_rt']]);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }
    
    public function data3()
    {
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        debug($whereKel, 1);exit;
        $sql = 'SELECT T.*, COALESCE(A.actual,0) as "Actual" 
            FROM (
                SELECT a."UserID", a."WilayahID", a."TargetKK"
                , CASE
                WHEN (b."TingkatWilayahID" = 6) THEN c.nama_rt
                ELSE null
                END AS "nama_rt"
                , CASE
                WHEN (b."TingkatWilayahID" = 6) THEN c.id_rt
                ELSE null
                END AS "id_rt"
                , CASE
                WHEN (b."TingkatWilayahID" = 6) THEN d.nama_rw
                WHEN (b."TingkatWilayahID" = 5) THEN d2.nama_rw
                ELSE null
                END AS "nama_rw"
                , CASE
                WHEN (b."TingkatWilayahID" = 6) THEN d.id_rw
                WHEN (b."TingkatWilayahID" = 5) THEN d2.id_rw
                ELSE null
                END AS "id_rw"
                ,CASE
                WHEN (b."TingkatWilayahID" = 6) THEN e.nama_kelurahan
                WHEN (b."TingkatWilayahID" = 5) THEN e2.nama_kelurahan
                ELSE null
                END AS "nama_kelurahan"
                ,CASE
                WHEN (b."TingkatWilayahID" = 6) THEN e.id_kelurahan
                WHEN (b."TingkatWilayahID" = 5) THEN e2.id_kelurahan
                ELSE null
                END AS "id_kelurahan"
                , b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON b."ID"=a."UserID" AND b."RoleID"=5 AND b."PeriodeSensus"='.request()->input('PeriodeSensus').'  
                LEFT  JOIN "RT" c ON c.id_rt=a."WilayahID"
                LEFT  JOIN "RW" d ON d.id_rw=c.id_rw
                LEFT  JOIN "Kelurahan" e ON e.id_kelurahan=d.id_kelurahan
                LEFT  JOIN "RW" d2 ON d2.id_rw=a."WilayahID"
                LEFT  JOIN "Kelurahan" e2 ON e2.id_kelurahan=d2.id_kelurahan
            ) T
            LEFT JOIN (
                SELECT periode_sensus
                , create_by
                , id_desa
                , id_rw
                , id_rt
                , count(*) as actual
                FROM mst_formulir 
                WHERE status_sensus=\'1\' AND periode_sensus=\''.request()->input('PeriodeSensus').'\' 
                GROUP BY periode_sensus, create_by, id_desa, id_rw, id_rt
            ) A
            ON A.create_by=T."UserName"
            AND COALESCE(A.id_desa,0)=COALESCE(T.id_kelurahan,0)
            AND COALESCE(A.id_rw,0)=COALESCE(T.id_rw,0)
            AND COALESCE(A.id_rt,0)=COALESCE(T.id_rt,0)
            WHERE T.id_kelurahan IN ('.$whereKel.')
            ';
            
        if (!empty(request()->input('RT'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
        } elseif (!empty(request()->input('RW'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
        } elseif (!empty(request()->input('Kelurahan'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
        }
        
        
        
        if (request()->input('print')==1) {
            $nama_wilayah = '';
            if (!empty(request()->input('RT'))) {
                $wilrow = \DB::table('v_rt')->where('id_rt', request()->input('RT'))->first();
                // $nama_wilayah = 'PROVINSI: ' . $wilrow->nama_provinsi . ', KOTA/KABUPATEN: ' . $wilrow->nama_kabupaten . ', KECAMATAN: ' . $wilrow->nama_kecamatan;
                $nama_wilayah .= 'DESA/KELURAHAN: ' . $wilrow->nama_kelurahan . ', RW: ' . $wilrow->nama_rw . ', RT: ' . $wilrow->nama_rt;
            } elseif (!empty(request()->input('RW'))) {
                $wilrow = \DB::table('v_rw')->where('id_rw', request()->input('RW'))->first();
                // $nama_wilayah = 'PROVINSI: ' . $wilrow->nama_provinsi . ', KOTA/KABUPATEN: ' . $wilrow->nama_kabupaten . ', KECAMATAN: ' . $wilrow->nama_kecamatan;
                $nama_wilayah .= 'DESA/KELURAHAN: ' . $wilrow->nama_kelurahan . ', RW: ' . $wilrow->nama_rw;
            } elseif (!empty(request()->input('Kelurahan'))) {
                $wilrow = \DB::table('v_kelurahan')->where('id_kelurahan', request()->input('Kelurahan'))->first();
                // $nama_wilayah = 'PROVINSI: ' . $wilrow->nama_provinsi . ', KOTA/KABUPATEN: ' . $wilrow->nama_kabupaten . ', KECAMATAN:' . $wilrow->nama_kecamatan;
                $nama_wilayah .= 'DESA/KELURAHAN: ' . $wilrow->nama_kelurahan;
            } else {
                $nama_wilayah = auth()->user()->wilayah->TingkatWilayah . ': ';
                $nama_wilayah .= implode(', ', auth()->user()->akseswilayah->pluck('nama_wilayah')->toArray());
            }
            $rows = \DB::select($sql);
            // return view('laporan.target_pdf',['rows'=>$rows, 'nama_wilayah'=>$nama_wilayah]);
            $pdf = PDF::loadview('laporan.target_pdf',['rows'=>$rows, 'nama_wilayah'=>$nama_wilayah]);
            return $pdf->stream('MonTargetTerdata.pdf');
        } else {
            $data = new jqGrid($sql, ['searchFields'=>['UserName', 'NamaLengkap']]);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }
    
    public function pendata()
    {
        $sql = 'SELECT a.*
                , c.nama_rt
                , d.nama_rw
                , e.nama_kelurahan
                , f.nama_kecamatan
                , g.nama_kabupaten
                , h.nama_provinsi
            FROM mst_formulir a  
            LEFT  JOIN "RT" c ON c.id_rt=a.id_rt
            LEFT  JOIN "RW" d ON d.id_rw=a.id_rw
            LEFT  JOIN "Kelurahan" e ON e.id_kelurahan=a.id_desa
            LEFT  JOIN "Kecamatan" f ON f.id_kecamatan=a.id_kecamatan
            LEFT  JOIN "Kabupaten" g ON g.id_kabupaten=a.id_kabupaten
            LEFT  JOIN "Provinsi" h ON h.id_provinsi=a.id_propinsi
            WHERE status_sensus=\'1\' AND periode_sensus=\''.request()->input('PeriodeSensus').'\' 
            AND CAST(create_by as VARCHAR)=CAST(\''.request()->input('Pendata').'\' as VARCHAR)
        ';
        $data = new DataTable($sql, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $result = $data->get();
        return $this->jsonOutput($result);
    }
}
