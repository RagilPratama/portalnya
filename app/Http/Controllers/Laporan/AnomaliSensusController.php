<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;
use App\Models\Laporan\StatusValid;
use App\Models\User;
use App\Models\Anomali;
use App\Models\UserAkses;

use App\Libraries\DataTable;
use App\Libraries\jqGrid;

use DB;
use PDF;

class AnomaliSensusController extends Controller
{
    public function index()
    {
        $userID = currentUser('ID');
        $indikator = \DB::table('Parameter')->where('Group', 'Anomali')->get();
        $periode = \DB::table('PeriodeSensus')->orderBy('Tahun', 'DESC')->get();
        $valperiode = currentUser('PeriodeSensus');
        $userAkses = UserAkses::where('UserID', $userID)->get()->pluck('WilayahID')->toArray();
        $valwilayah = count($userAkses)==1 ? $userAkses[0] : null;
        if (in_array(currentUser('RoleID'), [2,3])) { // check RoleID
            $valperiode = null;
            $valwilayah = null;
        }

        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();

        //$userPendata = $this->userPendata($userID);
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        return view('laporan.anomalisensus')->with(compact('indikator', 'periode', 'valperiode', 'wilayah', 'valwilayah', 'userPendata'));
        ;
    }

    public function data()
    {
        $result = [];
        $request = request()->all();
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $periode = request()->input('PeriodeSensus');
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        
       /*switch (request()->input('Indikator')) {
            case 1:
                $result = $this->indikatorPendidikan();
                break;
            case 2:
                $result = $this->indikatorPerkawinan();
                break;
            case 3:
                $result = $this->indikatorPekerjaan();
                break;
            case 4:
                $result = $this->indikatorKeluarga();
                break;
            case 5:
                $result = $this->indikatorKb();
                break;
        } */

        DB::statement('SELECT f_gen_data_anomali (:param1)', [
          'param1' => $periode
        ]);

        switch (request()->input('level')) {
            case 1:
                $result = $this->indikatorLevel1();
                break;
            case 2:
                $result = $this->indikatorLevel2();
                break;
            case 3:
                $result = $this->indikatorLevel3();
                break;
        } 

        return $this->jsonOutput(['rows'=>$result]);
    }

    public function indikatorLevel1()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $sql = '';

        $sql = 'select id_kelurahan, nama_kelurahan, 
                        sum(a.anomali_perkawinan) as anomali_perkawinan, sum(a.anomali_keluarga) as anomali_keluarga, 
                        sum(a.anomali_pendidikan) as anomali_pendidikan, sum(a.anomali_nik) as anomali_nik,
                        sum(a.anomali_perkerjaan) as anomali_perkerjaan
                    from data_anomali_temp a
                    where periode_sensus=\''.request()->input('PeriodeSensus').'\'
                    group by id_kelurahan, nama_kelurahan';

        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereIn = implode(',', $wilayah->pluck('id')->toArray());
        $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.')';
                            
        //debug($sql); exit;

        $rows = \DB::select($sql);
        return $rows;
    }    
    


    public function indikatorLevel2()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $sql = '';

        $sql = 'select id_kelurahan, nama_kelurahan, id_rw, nama_rw, 
                        sum(a.anomali_perkawinan) as anomali_perkawinan, sum(a.anomali_keluarga) as anomali_keluarga, 
                        sum(a.anomali_pendidikan) as anomali_pendidikan, sum(a.anomali_nik) as anomali_nik,
                        sum(a.anomali_perkerjaan) as anomali_perkerjaan
                    from data_anomali_temp a
                    where periode_sensus=\''.request()->input('PeriodeSensus').'\'
                      and id_kelurahan =\''.request()->input('Kelurahan').'\'                    
                    group by id_kelurahan, nama_kelurahan,id_rw, nama_rw';

        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereIn = implode(',', $wilayah->pluck('id')->toArray());
        $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.') ';
                            
        //debug($sql); exit;

        $rows = \DB::select($sql);
        return $rows;
    } 

    public function indikatorLevel3()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $sql = '';

        $sql = 'select id_kelurahan, nama_kelurahan, id_rw, nama_rw, id_rt, nama_rt,
                        sum(a.anomali_perkawinan) as anomali_perkawinan, sum(a.anomali_keluarga) as anomali_keluarga, 
                        sum(a.anomali_pendidikan) as anomali_pendidikan, sum(a.anomali_nik) as anomali_nik,
                        sum(a.anomali_perkerjaan) as anomali_perkerjaan
                    from data_anomali_temp a
                    where periode_sensus=\''.request()->input('PeriodeSensus').'\'
                      and id_kelurahan =\''.request()->input('Kelurahan').'\'   
                      and id_rw  =\''.request()->input('RW').'\'                   
                    group by id_kelurahan, nama_kelurahan,id_rw, nama_rw,  id_rt, nama_rt';

        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereIn = implode(',', $wilayah->pluck('id')->toArray());
        $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.') ';
                            
        //debug($sql); exit;

        $rows = \DB::select($sql);
        return $rows;
    } 


    public function data_pdf () {
        


        $sql = '';
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $periode = request()->input('PeriodeSensus');
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());

        $whereIn = implode(',', $wilayah->pluck('id')->toArray());

        DB::statement('SELECT f_gen_data_rep_anomali (:param1)', [
          'param1' => $periode
        ]);

        $sql = ' WITH RECURSIVE rel_tree AS (
                         SELECT a.*
                           FROM "data_anomali_rep_temp" a
                          WHERE a.parent = 0 and id IN ('.$whereIn.')
                        UNION ALL
                         SELECT c.*
                           FROM "data_anomali_rep_temp" c
                             JOIN rel_tree p ON c.Parent = p.id          
                        )
                 SELECT *
                   FROM rel_tree
                  ORDER BY rel_tree.orders, rel_tree.levels';

        //$sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.') ';

        $rows = \DB::select($sql);
        $results = [];

        foreach ($rows as $key => $value) {           
            $results = Arr::add($rows, $key,  $value);                    
        }           

        $nama_wilayah = '';
        if (!empty(request()->input('RT'))) {
            $wilrow = DB::table('v_rt')->where('id_rt', request()->input('RT'))->first();
        } elseif (!empty(request()->input('RW'))) {
            $wilrow = DB::table('v_rw')->where('id_rw', request()->input('RW'))->first();
        } elseif (!empty(request()->input('Kelurahan'))) {
            $wilrow = DB::table('v_kelurahan')->where('id_kelurahan', request()->input('Kelurahan'))->first();
        } else {
            $wilrow = collect(currentUser('AksesWilayah'))->first();
        }

        $pdf = PDF::loadview('laporan.target_pdf_anomali_v2', compact('rows', 'periode', 'nama_wilayah', 'wilrow'));
        return $pdf->stream('MonTargetTerdata.pdf');
        //return $result;
    }

    public function indikatorPendidikan()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $sql = 'WITH t1 AS (
                select  b.id_propinsi as id_provinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa as id_kelurahan
                , b.id_rw
                , b.id_rt
                , a.umur
                , a.jns_pendidikan
                , count(*) as cnt
                FROM mst_formulir_dtl a
                INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                WHERE a.status_anomali=\'1\'
                GROUP BY  b.id_propinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa
                , b.id_rw
                , b.id_rt
                , a.umur
                , a.jns_pendidikan
                ),

                t2 AS
                (
                SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Pendidikan\'
                ),

                t4 AS
                (
                SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                )

                SELECT t1.*, t2."Value" as indikator, t2."Code"
                , t4."Value" as indikator2, t4."Code" as umurx
                , COALESCE(t3.cnt,0) as qty
                FROM t1
                CROSS JOIN t2
                CROSS JOIN t4
                left JOIN t1 as t3 ON CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                AND CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur';

        $sql = 'select distinct x.id_kelurahan
                                        , nama_rt
                        , nama_rw
                        , nama_kelurahan
                        , nama_kecamatan
                        , nama_kabupaten
                        , nama_provinsi
                    , CONCAT(indikator,\'-\', indikator2) as minggus
                    , CONCAT("Code",\'-\', indikator) as indikators
                        , cast(x.id_rt as integer) as id_rt
                        , cast("Code" as integer) as "Code"
                        , indikator
                        , cast(umurx as integer) as umurx
                        , indikator2
                        , qty
                FROM ('.$sql.') x
                LEFT JOIN "RT" r ON r.id_rt=x.id_rt
                LEFT JOIN "RW" s ON s.id_rw=x.id_rw
                LEFT JOIN "Kelurahan" t ON t.id_kelurahan=x.id_kelurahan
                LEFT JOIN "Kecamatan" u ON u.id_kecamatan=x.id_kecamatan
                LEFT JOIN "Kabupaten" v ON v.id_kabupaten=x.id_kabupaten
                LEFT JOIN "Provinsi" w ON w.id_provinsi=x.id_provinsi';

        if (request()->input('JenisData')==1) {
            if (!empty(request()->input('RT'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
            } elseif (!empty(request()->input('RW'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
            } elseif (!empty(request()->input('Kelurahan'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
            } else {
                $model = new \App\Models\Master\Kelurahan();
                $wilayah = $model->getByUserID();
                $whereIn = implode(',', $wilayah->pluck('id')->toArray());
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.')';
            }
        } else {
            if (!empty(request()->input('Pendata'))) {
                //$sql = 'SELECT * FROM ('.$sql.') x WHERE create_by=\''.request()->input('Pendata').'\'';
            }
        }

        $sql = $sql.' order by cast(x.id_rt as integer), cast("Code" as integer) , cast(umurx as integer)';
        
        debug($sql); exit;

        $rows = \DB::select($sql);
        return $rows;
    }

    public function indikatorPerkawinan()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }


        $sql = 'WITH t1 AS (
                select b.id_frm
                , b.id_propinsi as id_provinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa as id_kelurahan
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.sts_kawin
                , coalesce(a.umur,0) as umur
                , count(*) as cnt
                FROM mst_formulir_dtl a
                INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                WHERE a.status_anomali=\'2\'
                GROUP BY b.id_frm
                , b.id_propinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.sts_kawin, coalesce(a.umur,0)
                ),

                t2 AS
                (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Perkawinan\'
                ),
                t4 AS
                (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                )

                SELECT t1.*, t2."Code", t2."Value" as Indikator, t4."Value" as indikator2, t4."Code" as umurx, COALESCE(t3.cnt,0) as qty
                FROM t1
                CROSS JOIN t2
                CROSS JOIN t4
                LEFT JOIN t1 as t3 ON t1.id_frm=t3.id_frm AND CAST(t3.sts_kawin AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                AND CAST(t3.sts_kawin AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur';

        $sql = 'select x.*
                , r.nama_rt
                , s.nama_rw
                , t.nama_kelurahan
                , u.nama_kecamatan
                , v.nama_kabupaten
                , w.nama_provinsi
                FROM ('.$sql.') x
                LEFT JOIN "RT" r ON r.id_rt=x.id_rt
                LEFT JOIN "RW" s ON s.id_rw=x.id_rw
                LEFT JOIN "Kelurahan" t ON t.id_kelurahan=x.id_kelurahan
                LEFT JOIN "Kecamatan" u ON u.id_kecamatan=x.id_kecamatan
                LEFT JOIN "Kabupaten" v ON v.id_kabupaten=x.id_kabupaten
                LEFT JOIN "Provinsi" w ON w.id_provinsi=x.id_provinsi';

        if (request()->input('JenisData')==1) {
            if (!empty(request()->input('RT'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
            } elseif (!empty(request()->input('RW'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
            } elseif (!empty(request()->input('Kelurahan'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
            } else {
                $model = new \App\Models\Master\Kelurahan();
                $wilayah = $model->getByUserID();
                $whereIn = implode(',', $wilayah->pluck('id')->toArray());
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.')';
            }
        } else {
            if (!empty(request()->input('Pendata'))) {
               //$sql = 'SELECT * FROM ('.$sql.') x WHERE create_by=\''.request()->input('Pendata').'\'';
            }
        }

        $sql = $sql.' order by cast(x.id_rt as integer), cast("Code" as integer) , cast(umurx as integer)';
        $rows = \DB::select($sql);
        return $rows;
    }

    public function indikatorPekerjaan()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $sql = 'WITH t1 AS (
                select b.id_frm
                , b.id_propinsi as id_provinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa as id_kelurahan
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.id_pekerjaan
                , coalesce(a.umur,0) as umur
                , count(*) as cnt
                FROM mst_formulir_dtl a
                INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                WHERE a.status_anomali=\'3\'
                GROUP BY b.id_frm
                , b.id_propinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.id_pekerjaan, coalesce(a.umur,0)
                ),

                t2 AS
                (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Pekerjaan\'
                ),

                t4 AS
                (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                )

                SELECT t1.*, t2."Code", t2."Value" as Indikator, t4."Value" as indikator2, t4."Code" as umurx, COALESCE(t3.cnt,0) as qty
                FROM t1
                CROSS JOIN t2
                CROSS JOIN t4
                LEFT JOIN t1 as t3 ON t1.id_frm=t3.id_frm AND CAST(t3.id_pekerjaan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                AND CAST(t3.id_pekerjaan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur';

        $sql = 'select x.*
                , r.nama_rt
                , s.nama_rw
                , t.nama_kelurahan
                , u.nama_kecamatan
                , v.nama_kabupaten
                , w.nama_provinsi
                FROM ('.$sql.') x
                LEFT JOIN "RT" r ON r.id_rt=x.id_rt
                LEFT JOIN "RW" s ON s.id_rw=x.id_rw
                LEFT JOIN "Kelurahan" t ON t.id_kelurahan=x.id_kelurahan
                LEFT JOIN "Kecamatan" u ON u.id_kecamatan=x.id_kecamatan
                LEFT JOIN "Kabupaten" v ON v.id_kabupaten=x.id_kabupaten
                LEFT JOIN "Provinsi" w ON w.id_provinsi=x.id_provinsi';

        if (request()->input('JenisData')==1) {
            if (!empty(request()->input('RT'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
            } elseif (!empty(request()->input('RW'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
            } elseif (!empty(request()->input('Kelurahan'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
            } else {
                $model = new \App\Models\Master\Kelurahan();
                $wilayah = $model->getByUserID();
                $whereIn = implode(',', $wilayah->pluck('id')->toArray());
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.')';
            }
        } else {
            if (!empty(request()->input('Pendata'))) {
                //$sql = 'SELECT * FROM ('.$sql.') x WHERE create_by=\''.request()->input('Pendata').'\'';
            }
        }

        $sql = $sql.' order by cast(x.id_rt as integer), cast("Code" as integer) , cast(umurx as integer)';
        $rows = \DB::select($sql);
        return $rows;
    }


    public function indikatorKeluarga()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $sql = 'WITH t1 AS (
                select b.id_frm
                , b.id_propinsi as id_provinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa as id_kelurahan
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.sts_hubungan
                , coalesce(a.umur,0) as umur
                , count(*) as cnt
                FROM mst_formulir_dtl a
                INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                WHERE a.status_anomali=\'4\'
                GROUP BY b.id_frm
                , b.id_propinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.sts_hubungan, coalesce(a.umur,0)
                ),

                t2 AS
                (
                SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Keluarga\'
                ),

                t4 AS
                (
                SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                )

                SELECT t1.*, t2."Code", t2."Value" as Indikator, t4."Value" as indikator2, t4."Code" as umurx, COALESCE(t3.cnt,0) as qty
                FROM t1
                CROSS JOIN t2
                CROSS JOIN t4
                LEFT JOIN t1 as t3 ON t1.id_frm=t3.id_frm AND CAST(t3.sts_hubungan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                AND CAST(t3.sts_hubungan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur';

        $sql = 'select x.*
                , r.nama_rt
                , s.nama_rw
                , t.nama_kelurahan
                , u.nama_kecamatan
                , v.nama_kabupaten
                , w.nama_provinsi
                FROM ('.$sql.') x
                LEFT JOIN "RT" r ON r.id_rt=x.id_rt
                LEFT JOIN "RW" s ON s.id_rw=x.id_rw
                LEFT JOIN "Kelurahan" t ON t.id_kelurahan=x.id_kelurahan
                LEFT JOIN "Kecamatan" u ON u.id_kecamatan=x.id_kecamatan
                LEFT JOIN "Kabupaten" v ON v.id_kabupaten=x.id_kabupaten
                LEFT JOIN "Provinsi" w ON w.id_provinsi=x.id_provinsi
                ';

        if (request()->input('JenisData')==1) {
            if (!empty(request()->input('RT'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
            } elseif (!empty(request()->input('RW'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
            } elseif (!empty(request()->input('Kelurahan'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
            } else {
                $model = new \App\Models\Master\Kelurahan();
                $wilayah = $model->getByUserID();
                $whereIn = implode(',', $wilayah->pluck('id')->toArray());
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.')';
            }
        } else {
            if (!empty(request()->input('Pendata'))) {
                //$sql = 'SELECT * FROM ('.$sql.') x WHERE create_by=\''.request()->input('Pendata').'\'';
            }
        }

        $sql = $sql.' order by cast(x.id_rt as integer), cast("Code" as integer) , cast(umurx as integer)';
        $rows = \DB::select($sql);
        return $rows;
    }


    public function indikatorKb()
    {
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $sql = 'select  x.id_kelurahan
              , coalesce(x.id_provinsi,\'0\')  as id_provinsi
                            , x.id_kecamatan
                            , x.id_rw
                            , x.id_rt
              , \'Jawaban\' as indikator
              , x."Answer" as indikator2
              , r.nama_rt
              , s.nama_rw
              , t.nama_kelurahan
              , u.nama_kecamatan
              , v.nama_kabupaten
              , coalesce(w.nama_provinsi,\'KOSONG\') as nama_provinsi
              , cast(x.id_rt as integer) as id_rt
              , cast("id_answer" as integer) as "Code"
              , cast("id_answer" as integer) as "umurx"
              , CONCAT("id_answer",\'-\', x."Answer") as minggus
              , count(qty) as qty
              FROM ( select x.id_provinsi, x.id_kabupaten, x.id_kelurahan, x.id_rw, x.id_rt, x.id_kecamatan,
                    x.id_frm, x.nama_anggotakel, x.jenis_kelamin, x.sts_hubungan
                    , x.sts_kawin, x.usia_kawin, x.id_kb, x.create_by, x.id_answer, x."Answer"
                     , count(1) qty from (select distinct
                     b.id_propinsi as id_provinsi, b.id_kabupaten, b.id_desa as id_kelurahan, b.id_rw, b.id_rt, b.id_kecamatan,
                     a.id_frm, a.nama_anggotakel, a.jenis_kelamin, a.sts_hubungan
                     , a.sts_kawin, a.usia_kawin, c.id_kb, coalesce(d.id_answer,\'0\') as id_answer, b.create_by,
                     case when  coalesce(d.id_answer,\'0\') = \'0\' then \'Tidak Jawab\' when coalesce(d.id_answer,\'0\') = \'1\' then \'Ya\' else \'Tidak\' end as "Answer",
                     count(1) qty
              FROM mst_formulir_dtl a
              join mst_formulir b on  a.id_frm = b.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
              left join frm_kb c on c.id_frm = a.id_frm and c.id_kb = \'4\'
              left join frm_kb_answer d on d.id_frm = a.id_frm and c.id_kb = \'4\' and d.id_frm = c.id_frm and d.id_kb = c.id_kb
              where a.sts_hubungan = 2 and a.jenis_kelamin = \'2\'
              group by a.id_frm, a.nama_anggotakel, a.jenis_kelamin, a.sts_hubungan
                     , a.sts_kawin, a.usia_kawin, c.id_kb, b.create_by,
                     d.id_answer, b.id_propinsi, b.id_kabupaten, b.id_desa, b.id_rw, b.id_rt, b.id_kecamatan
              order by c.id_kb, a.id_frm, coalesce(d.id_answer,\'0\'), a.nama_anggotakel, a.jenis_kelamin, a.sts_hubungan
                     , a.sts_kawin, a.usia_kawin) x
               group by x.id_provinsi, x.id_kabupaten, x.id_kelurahan, x.id_rw, x.id_rt, x.id_kecamatan,
                       x.id_frm, x.nama_anggotakel, x.jenis_kelamin, x.sts_hubungan
                      , x.sts_kawin, x.usia_kawin, x.id_kb, x.create_by,x.id_answer ,x."Answer" ) x
              LEFT JOIN "RT" r ON r.id_rt=x.id_rt
              LEFT JOIN "RW" s ON s.id_rw=x.id_rw
              LEFT JOIN "Kelurahan" t ON t.id_kelurahan=x.id_kelurahan
              LEFT JOIN "Kecamatan" u ON u.id_kecamatan=x.id_kecamatan
              LEFT JOIN "Kabupaten" v ON v.id_kabupaten=x.id_kabupaten
              LEFT JOIN "Provinsi" w ON w.id_provinsi=x.id_provinsi
              group by x.id_kelurahan
              , x.id_provinsi
                            , x.id_kecamatan
                            , x.id_rw
                            , x.id_rt
              , x."Answer"
              , r.nama_rt
              , s.nama_rw
              , t.nama_kelurahan
              , u.nama_kecamatan
              , v.nama_kabupaten
              , w.nama_provinsi
              , cast(x.id_rt as integer)
              , cast("id_answer" as integer) ';

        if (request()->input('JenisData')==1) {
            if (!empty(request()->input('RT'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
            } elseif (!empty(request()->input('RW'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
            } elseif (!empty(request()->input('Kelurahan'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
            } else {
                $model = new \App\Models\Master\Kelurahan();
                $wilayah = $model->getByUserID();
                $whereIn = implode(',', $wilayah->pluck('id')->toArray());
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.')';
            }
        } else {
            if (!empty(request()->input('Pendata'))) {
               //$sql = 'SELECT * FROM ('.$sql.') x WHERE create_by=\''.request()->input('Pendata').'\'';
            }
        }

        $rows = \DB::select($sql);
        return $rows;
    }


    private function indikator($request)
    {
        $data = [];
        $rws = [18283, 18284];
        $rws2 = [17671,17672];

        $groupindikator = [
            1 => 'Pendidikan',
            2 => 'Perkawinan',
            3 => 'Pekerjaan',
        ];
        $group = \DB::table('Parameter')->where('Group', $groupindikator[$request['Indikator']])->pluck('Value', 'Code');

        // $group = \DB::table('Parameter')->where('Group', 'Pendidikan')->pluck('Value','Code');

        $model = new \App\Models\Master\RT();

        foreach ($rws as $rw) {
            $rowrt = $model->where('id_rw', $rw)->get();
            foreach ($rowrt as $row) {
                for ($cnt=1; $cnt<=count($group); $cnt++) {
                    $item = [];
                    // $model = new \App\Models\Master\RT();
                    // $row = $model->where('id_rw',$rw)->get()->random();
                    $item['provinsi'] = $row->ProvinsiID;
                    $item['nama_provinsi'] = $row->NamaProvinsi;
                    $item['kabupaten'] = $row->KabupatenID;
                    $item['nama_kabupaten'] = $row->NamaKabupaten;
                    $item['kecamatan'] = $row->KecamatanID;
                    $item['nama_kecamatan'] = $row->NamaKecamatan;
                    $item['kelurahan'] = $row->KelurahanID;
                    $item['nama_kelurahan'] = $row->NamaKelurahan;
                    $item['rw'] = $row->id_rw;
                    $item['nama_rw'] = $row->NamaRW;
                    $item['rt'] = $row->id_rt;
                    $item['nama_rt'] = $row->nama_rt;
                    $item['indikator'] = $group[$cnt];
                    $item['qty'] = rand(10, 100);
                    $data[] = $item;
                }
            }
        }
        foreach ($rws2 as $rw) {
            $rowrt = $model->where('id_rw', $rw)->get();
            foreach ($rowrt as $row) {
                for ($cnt=1; $cnt<=count($group); $cnt++) {
                    $item = [];
                    // $model = new \App\Models\Master\RT();
                    // $row = $model->where('id_rw',$rw)->get()->random();
                    $item['provinsi'] = $row->ProvinsiID;
                    $item['nama_provinsi'] = $row->NamaProvinsi;
                    $item['kabupaten'] = $row->KabupatenID;
                    $item['nama_kabupaten'] = $row->NamaKabupaten;
                    $item['kecamatan'] = $row->KecamatanID;
                    $item['nama_kecamatan'] = $row->NamaKecamatan;
                    $item['kelurahan'] = $row->KelurahanID;
                    $item['nama_kelurahan'] = $row->NamaKelurahan;
                    $item['rw'] = $row->id_rw;
                    $item['nama_rw'] = $row->NamaRW;
                    $item['rt'] = $row->id_rt;
                    $item['nama_rt'] = $row->nama_rt;
                    $item['indikator'] = $group[$cnt];
                    $item['qty'] = rand(10, 100);
                    $data[] = $item;
                }
            }
        }
        return $data;
    }

    public function data2()
    {
        $anomali = [
            1 => ['Group'=>'Pendidikan', 'field'=>'jns_pendidikan'],
            2 => ['Group'=>'Perkawinan', 'field'=>'sts_kawin'],
            3 => ['Group'=>'Pekerjaan', 'field'=>'id_pekerjaan'],
            4 => ['Group'=>'KK', 'field'=>'jns_pendidikan'],
            5 => ['Group'=>'KB', 'field'=>'jns_pendidikan'],
        ];
        $sql = 'WITH t1 AS (
                select b.id_frm
                , b.id_propinsi as id_provinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa as id_kelurahan
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.jns_pendidikan
                , count(*) as cnt
                FROM mst_formulir_dtl a
                INNER JOIN mst_formulir b ON b.id_frm=a.id_frm
                WHERE a.status_anomali=\'1\'
                GROUP BY b.id_frm
                , b.id_propinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa
                , b.id_rw
                , b.id_rt
                , b.create_by
                , a.jns_pendidikan
                ),

                t2 AS
                (
                    SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Pendidikan\'
                )

                SELECT t1.*, t2."Value" as Indikator, COALESCE(t3.cnt,0) as qty
                FROM t1
                CROSS JOIN t2
                LEFT JOIN t1 as t3 ON t1.id_frm=t3.id_frm AND t3.jns_pendidikan=CAST(t2."Code" AS INTEGER)';

        $row = \DB::select($sql);
    }


    private function userPendata($parentID)
    {
        $childUser = [];
        $this->getChildUser($childUser, $parentID);
        $userPendata = array_filter($childUser, function ($array) {
            if ($array['RoleID']==5) {
                return $array;
            }
        });
        return $userPendata;
    }

    private function getChildUser(&$users, $parentID)
    {
        $rows = User::select('ID', 'UserName', 'NamaLengkap', 'RoleID')->where('CreatedBy', $parentID)->get();
        foreach ($rows as $row) {
            $users[] = $row->toArray();
            $this->getChildUser($users, $row->ID);
        }
    }


    public function validmefirst()
    {
        
        try {
            //\DB::table('mst_formulir')->where('id_frm', $id)->get()->count();
            
            $id = request()->get('id_frm');
            $nama_anggotakel = request()->get('nama_anggotakel');

            $count = DB::table('Anomali_Dtl')->where('id_frm', $id)->where('status_anomali','<>', '1')->distinct()->get()->count();

            if ($count > 1) {
                $result = ['status' => true,'message' => 'Data ini memiliki Anomali Lbh dari satu, Proses Validasi Data?'];
            } 

            $result = ['status' => true,'message' => 'Proses Validasi Data?'];
        } catch (\Exception $e) {
            $result = ['status' => false,'message' => $e];
        }

        return $this->jsonOutput($result);
    }

    public function validme()
    {
        try {
            $id = request()->get('id_frm');
            $nama_anggotakel = request()->get('nama_anggotakel');

            $count = DB::table('Anomali_Dtl')->where('id_frm', $id)->where('status_anomali','<>', '1')->distinct()->get()->count();
            
            if ($count > 1) {
                \DB::table('Anomali_Dtl')->where('id_frm', $id, )->where('status_anomali','<>', '1')->where('nama_anggotakel',$nama_anggotakel)->update( ['status_anomali' => 1]);
            } else {
                \DB::table('Anomali_Dtl')->where('id_frm', $id, )->where('status_anomali','<>', '1')->where('nama_anggotakel',$nama_anggotakel)->update( [ 'status_anomali' => 1   ]  );

                \DB::table('mst_formulir')->where('id_frm', $id)->update(
                    [   'status_sensus'    => 1,
                        'update_date'      => date('Y-m-d H:i:s'),
                        'update_by'        => currentUser('UserName')
                    ]
                );

            }    
            
            $result = ['status' => true,'message' => 'Your data has been validate.'];
            
            
        } catch (\Exception $e) {
            $result = ['status' => false,'message' => $e];
        }

        return $this->jsonOutput($result);
    }


 public function dataPaging()
    {
        $sql = '';
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        $sql = '';

        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $request = request()->all();
        switch (request()->input('iCol')) {
            case 7:
                $sql = 'select id_frm, nik, nama_anggotakel, no_urutnik as no_urutkel, nama_provinsi, nama_kabupaten , nama_rw, nama_rt, alamat1, no_urutrmh, status_sensus
                from v_anomali_nik
                where id_rt = \''.request()->input('RT').'\' 
                and id_rw = \''.request()->input('RW').'\' 
                and id_kelurahan = \''.request()->input('Kelurahan').'\' 
                limit '.request()->input('limit');
                break;
            case 6:
                $sql = 'select id_frm, nik, nama_anggotakel, no_urutnik as no_urutkel, nama_provinsi, nama_kabupaten , nama_rw, nama_rt, alamat1, no_urutrmh, status_sensus
                from v_anomali_keluarga
                where id_rt = \''.request()->input('RT').'\' 
                and id_rw = \''.request()->input('RW').'\' 
                and id_kelurahan = \''.request()->input('Kelurahan').'\' 
                limit '.request()->input('limit');
                break;
            case 5:
                $sql = 'select id_frm, nik, nama_anggotakel, no_urutnik as no_urutkel, nama_provinsi, nama_kabupaten , nama_rw, nama_rt, alamat1, no_urutrmh, status_sensus
                from v_anomali_perkerjaan
                where id_rt = \''.request()->input('RT').'\' 
                and id_rw = \''.request()->input('RW').'\' 
                and id_kelurahan = \''.request()->input('Kelurahan').'\' 
                limit '.request()->input('limit');
                break;
            case 4:
                 $sql = 'select id_frm, nik, nama_anggotakel, no_urutnik as no_urutkel, nama_provinsi, nama_kabupaten , nama_rw, nama_rt, alamat1, no_urutrmh, status_sensus
                from v_anomali_perkawinan
                where id_rt = \''.request()->input('RT').'\' 
                and id_rw = \''.request()->input('RW').'\' 
                and id_kelurahan = \''.request()->input('Kelurahan').'\' 
                limit '.request()->input('limit');
                break;
            case 3:
                 $sql = 'select id_frm, nik, nama_anggotakel, no_urutnik as no_urutkel, nama_provinsi, nama_kabupaten , nama_rw, nama_rt, alamat1, no_urutrmh, status_sensus
                from v_anomali_pendidikan
                where id_rt = \''.request()->input('RT').'\' 
                and id_rw = \''.request()->input('RW').'\' 
                and id_kelurahan = \''.request()->input('Kelurahan').'\' 
                limit '.request()->input('limit');
                break;
        }

        

        

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        $data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $users = $data->get();

        return $this->jsonOutput($users);
    }


    public function dataPaging_old()
    {
        $sql = '';
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        if (!empty(request()->input('Pendata'))) {
                $sqladd = ' and create_by= \''.request()->input('Pendata').'\' ';
        } else {  $sqladd = ''; }

        $request = request()->all();
        switch (request()->input('Indikator')) {
            case 1:
                //$result = $this->indikatorPendidikan();
                //break;
                $Group = '1';
                $GroupCode = request()->input('Code');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                $UmurCode = request()->input('umur');
                break;
            case 2:
                //$result = $this->indikatorPerkawinan();
                $Group = '2';
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                break;
            case 3:
                //$result = $this->indikatorPekerjaan();
                $Group = '3';
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                break;
            case 4:
                //$result = $this->indikatorKeluarga();
                $Group = '4';
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                break;
            case 5:
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $code = request()->input('Code');
                //$result = $this->indikatorKb();
                break;
        }

        $sql = '';

        // Kondisi per Anomali untuk field..
        switch (request()->input('Indikator')) {
              case 1:
                  $sql = '';
                  $sql = 'select aa.* , r.nama_rt, s.nama_rw
                          , t.nama_kelurahan, u.nama_kecamatan, v.nama_kabupaten
                          , w.nama_provinsi
                          from ( WITH t1 AS (
                          select  b.id_frm, b.id_propinsi as id_provinsi
                          , b.id_kabupaten
                          , b.id_kecamatan
                          , b.id_desa as id_kelurahan
                          , b.id_rw
                          , b.id_rt
                          , a.umur
                          , a.jns_pendidikan,
                                        a.nik, a.nama_anggotakel,
                          b.status_sensus as status_sensus
                          , count(*) as cnt
                          FROM mst_formulir_dtl a
                          INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                          WHERE a.status_anomali=\''.$Group.'\'
                          GROUP BY  b.id_propinsi
                          , b.id_kabupaten
                          , b.id_kecamatan
                          , b.id_desa
                          , b.id_rw
                          , b.id_rt
                          , a.umur
                          , a.jns_pendidikan, b.id_frm,
                                        a.nik, a.nama_anggotakel,
                          b.status_sensus
                          ), t2 AS   (
                          SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Pendidikan\'
                          ), t4 AS (
                          SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                          )
                          SELECT t1.*, t2."Value" as indikator, t2."Code" , t4."Value" as indikator2, t4."Code" as umurx , COALESCE(t3.cnt,0) as qty
                          FROM t1
                          CROSS JOIN t2
                          CROSS JOIN t4
                          left JOIN t1 as t3 ON CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                          AND CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur ) aa
                          LEFT JOIN "RT" r ON r.id_rt=aa.id_rt
                          LEFT JOIN "RW" s ON s.id_rw=aa.id_rw
                          LEFT JOIN "Kelurahan" t ON t.id_kelurahan=aa.id_kelurahan
                          LEFT JOIN "Kecamatan" u ON u.id_kecamatan=aa.id_kecamatan
                          LEFT JOIN "Kabupaten" v ON v.id_kabupaten=aa.id_kabupaten
                          LEFT JOIN "Provinsi" w ON w.id_provinsi=aa.id_provinsi
                                        where qty > 0 and umur = \''.$UmurCode.'\'  and "Code" = \''.$GroupCode.'\'';

                  break;
              case 2:
                    $sql = '';
                    $sql = 'select aa.* , r.nama_rt, s.nama_rw
                            , t.nama_kelurahan, u.nama_kecamatan, v.nama_kabupaten
                            , w.nama_provinsi
                            from ( WITH t1 AS (
                            select  b.id_propinsi as id_provinsi
                            , b.id_kabupaten
                            , b.id_kecamatan
                            , b.id_desa as id_kelurahan
                            , b.id_rw
                            , b.id_rt
                            , a.umur
                            , a.sts_kawin,
                            a.nik, a.nama_anggotakel,
                            b.status_sensus as status_sensus
                            , count(*) as cnt
                            FROM mst_formulir_dtl a
                            INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                            WHERE a.status_anomali=\''.$Group.'\'
                            GROUP BY  b.id_propinsi
                            , b.id_kabupaten
                            , b.id_kecamatan
                            , b.id_desa
                            , b.id_rw
                            , b.id_rt
                            , a.umur
                            , a.sts_kawin,
                            a.nik, a.nama_anggotakel,
                            b.status_sensus
                            ), t2 AS   (
                            SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Pendidikan\'
                            ), t4 AS (
                            SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                            )
                            SELECT t1.*, t2."Value" as indikator, t2."Code" , t4."Value" as indikator2, t4."Code" as umurx , COALESCE(t3.cnt,0) as qty
                            FROM t1
                            CROSS JOIN t2
                            CROSS JOIN t4
                            left JOIN t1 as t3 ON CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                            AND CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur ) aa
                            LEFT JOIN "RT" r ON r.id_rt=aa.id_rt
                            LEFT JOIN "RW" s ON s.id_rw=aa.id_rw
                            LEFT JOIN "Kelurahan" t ON t.id_kelurahan=aa.id_kelurahan
                            LEFT JOIN "Kecamatan" u ON u.id_kecamatan=aa.id_kecamatan
                            LEFT JOIN "Kabupaten" v ON v.id_kabupaten=aa.id_kabupaten
                            LEFT JOIN "Provinsi" w ON w.id_provinsi=aa.id_provinsi
                            where qty > 0 and umur = \''.$UmurCode.'\'  and "Code" = \''.$GroupCode.'\'';

                  break;
              case 3: //id_pekerjaan
                      $sql = '';
                      $sql = 'select aa.* , r.nama_rt, s.nama_rw
                              , t.nama_kelurahan, u.nama_kecamatan, v.nama_kabupaten
                              , w.nama_provinsi
                              from ( WITH t1 AS (
                              select  b.id_propinsi as id_provinsi
                              , b.id_kabupaten
                              , b.id_kecamatan
                              , b.id_desa as id_kelurahan
                              , b.id_rw
                              , b.id_rt
                              , a.umur
                              , a.id_pekerjaan,
                              a.nik, a.nama_anggotakel,
                              b.status_sensus as status_sensus
                              , count(*) as cnt
                              FROM mst_formulir_dtl a
                              INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                              WHERE a.status_anomali=\''.$Group.'\'
                              GROUP BY  b.id_propinsi
                              , b.id_kabupaten
                              , b.id_kecamatan
                              , b.id_desa
                              , b.id_rw
                              , b.id_rt
                              , a.umur
                              , a.id_pekerjaan,
                              a.nik, a.nama_anggotakel,
                              b.status_sensus
                              ), t2 AS   (
                              SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Pendidikan\'
                              ), t4 AS (
                              SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                              )
                              SELECT t1.*, t2."Value" as indikator, t2."Code" , t4."Value" as indikator2, t4."Code" as umurx , COALESCE(t3.cnt,0) as qty
                              FROM t1
                              CROSS JOIN t2
                              CROSS JOIN t4
                              left JOIN t1 as t3 ON CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                              AND CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur ) aa
                              LEFT JOIN "RT" r ON r.id_rt=aa.id_rt
                              LEFT JOIN "RW" s ON s.id_rw=aa.id_rw
                              LEFT JOIN "Kelurahan" t ON t.id_kelurahan=aa.id_kelurahan
                              LEFT JOIN "Kecamatan" u ON u.id_kecamatan=aa.id_kecamatan
                              LEFT JOIN "Kabupaten" v ON v.id_kabupaten=aa.id_kabupaten
                              LEFT JOIN "Provinsi" w ON w.id_provinsi=aa.id_provinsi
                              where qty > 0 and umur = \''.$UmurCode.'\'  and "Code" = \''.$GroupCode.'\'';

                  break;
              case 4:
                      $sql = '';
                      $sql = 'select aa.* , r.nama_rt, s.nama_rw
                              , t.nama_kelurahan, u.nama_kecamatan, v.nama_kabupaten
                              , w.nama_provinsi
                              from ( WITH t1 AS (
                              select  b.id_propinsi as id_provinsi
                              , b.id_kabupaten
                              , b.id_kecamatan
                              , b.id_desa as id_kelurahan
                              , b.id_rw
                              , b.id_rt
                              , a.umur
                              , a.sts_hubungan,
                              a.nik, a.nama_anggotakel,
                              b.status_sensus as status_sensus
                              , count(*) as cnt
                              FROM mst_formulir_dtl a
                              INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                              WHERE a.status_anomali=\''.$Group.'\'
                              GROUP BY  b.id_propinsi
                              , b.id_kabupaten
                              , b.id_kecamatan
                              , b.id_desa
                              , b.id_rw
                              , b.id_rt
                              , a.umur
                              , a.sts_hubungan,
                              a.nik, a.nama_anggotakel,
                              b.status_sensus
                              ), t2 AS   (
                              SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Pendidikan\'
                              ), t4 AS (
                              SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                              )
                              SELECT t1.*, t2."Value" as indikator, t2."Code" , t4."Value" as indikator2, t4."Code" as umurx , COALESCE(t3.cnt,0) as qty
                              FROM t1
                              CROSS JOIN t2
                              CROSS JOIN t4
                              left JOIN t1 as t3 ON CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                              AND CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur ) aa
                              LEFT JOIN "RT" r ON r.id_rt=aa.id_rt
                              LEFT JOIN "RW" s ON s.id_rw=aa.id_rw
                              LEFT JOIN "Kelurahan" t ON t.id_kelurahan=aa.id_kelurahan
                              LEFT JOIN "Kecamatan" u ON u.id_kecamatan=aa.id_kecamatan
                              LEFT JOIN "Kabupaten" v ON v.id_kabupaten=aa.id_kabupaten
                              LEFT JOIN "Provinsi" w ON w.id_provinsi=aa.id_provinsi
                              where qty > 0 and umur = \''.$UmurCode.'\'  and "Code" = \''.$GroupCode.'\'';

                  break;
                  case 5:
                      $sql = '';
                      $sql = 'select  b.id_frm, b.id_propinsi as id_provinsi
                              , b.id_kabupaten
                              , b.id_kecamatan
                              , b.id_desa as id_kelurahan
                              , b.id_rw
                              , b.id_rt
                              , b.create_by
                              , r.nama_rt
                              , s.nama_rw
                              , t.nama_kelurahan
                              , u.nama_kecamatan
                              , v.nama_kabupaten
                              , w.nama_provinsi
                              , a.umur
                              , a.jns_pendidikan, a.nik, a.nama_anggotakel,
                              b.status_sensus as status_sensus
                              FROM mst_formulir_dtl a
                              INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\') '.$sqladd .'
                              LEFT JOIN "RT" r ON r.id_rt=b.id_rt
                              LEFT JOIN "RW" s ON s.id_rw=b.id_rw
                              LEFT JOIN "Kelurahan" t ON t.id_kelurahan=b.id_desa
                              LEFT JOIN "Kecamatan" u ON u.id_kecamatan=b.id_kecamatan
                              LEFT JOIN "Kabupaten" v ON v.id_kabupaten=b.id_kabupaten
                              LEFT JOIN "Provinsi" w ON w.id_provinsi=b.id_propinsi
                              left join frm_kb c on c.id_frm = a.id_frm and c.id_kb = \'4\'
                              left join frm_kb_answer d on d.id_frm = a.id_frm and c.id_kb = \'4\' and d.id_frm = c.id_frm and d.id_kb = c.id_kb
                              WHERE a.sts_hubungan = 2 and a.jenis_kelamin = \'2\' and cast(coalesce("id_answer",\'0\') as integer) = '.$code;
                      break;
                }

        if (request()->input('JenisData')==1) {
            if (!empty(request()->input('RT'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
            } elseif (!empty(request()->input('RW'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
            } elseif (!empty(request()->input('Kelurahan'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
            } else {
                $model = new \App\Models\Master\Kelurahan();
                $wilayah = $model->getByUserID();
                $whereIn = implode(',', $wilayah->pluck('id')->toArray());
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan IN ('.$whereIn.')';
            }
        } else {
            if (!empty(request()->input('Pendata'))) {
                //$sql = 'SELECT * FROM ('.$sql.') x WHERE create_by=\''.request()->input('Pendata').'\'';
            }
        }

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        $data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $users = $data->get();

        return $this->jsonOutput($users);
    }


    public function cetak()
    {
        $usermodel = new User();
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $whereKel = implode(',', $wilayah->pluck('id')->toArray());

        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        switch (request()->input('Indikator')) {
            case 1:
                //$result = $this->indikatorPendidikan();
                //break;
                $Group = 'Pendidikan';
                $GroupCode = request()->input('Code');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                $UmurCode = request()->input('umur');
                break;
            case 2:
                //$result = $this->indikatorPerkawinan();
                $Group = 'Perkawinan';
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                break;
            case 3:
                //$result = $this->indikatorPekerjaan();
                $Group = 'Pekerjaan';
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                break;
            case 4:
                //$result = $this->indikatorKeluarga();
                $Group = 'Keluarga';
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                break;
            case 5:
                $Group = 'Keluarga';
                $GroupCode = request()->input('Code');
                $UmurCode = request()->input('umur');
                $provinsi = request()->input('Provinsi');
                $kabupaten = request()->input('Kabupaten');
                $kecamatan = request()->input('Kecamatan');
                $kelurahan = request()->input('Kelurahan');
                $RW = request()->input('RW');
                $RT = request()->input('RT');
                break;
        }

        $sql = 'WITH t1 AS (
                select  b.id_propinsi as id_provinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa as id_kelurahan
                , b.id_rw
                , b.id_rt
                , a.umur
                , a.jns_pendidikan
                , count(*) as cnt
                FROM mst_formulir_dtl a
                INNER JOIN mst_formulir b ON b.id_frm=a.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\')
                WHERE a.status_anomali=\'1\'
                GROUP BY  b.id_propinsi
                , b.id_kabupaten
                , b.id_kecamatan
                , b.id_desa
                , b.id_rw
                , b.id_rt
                , a.umur
                , a.jns_pendidikan
                ),

                t2 AS
                (
                SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\''.$Group.'\'
                ),

                t4 AS
                (
                SELECT "Code", "Value" FROM "Parameter" WHERE "Group"=\'Umur\'
                )

                SELECT t1.*, t2."Value" as indikator, cast(t2."Code" as integer) as "Code"
                , t4."Value" as indikator2, cast(t4."Code" as integer) as umurx
                , COALESCE(t3.cnt,0) as qty
                FROM t1
                CROSS JOIN t2
                CROSS JOIN t4
                left JOIN t1 as t3 ON CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t1.umur
                AND CAST(t3.jns_pendidikan AS INTEGER)=CAST(t2."Code" AS INTEGER) and cast( t4."Code" as INTEGER) = t3.umur';

        $sql = 'select distinct x.id_kelurahan
                    , nama_rt
                        , nama_rw
                        , nama_kelurahan
                        , nama_kecamatan
                        , nama_kabupaten
                        , nama_provinsi
                    , CONCAT(indikator,\'-\', indikator2) as minggus
                        , cast(x.id_rt as integer) as id_rt
                        , cast("Code" as integer) as "Code"
                        , indikator
                        , cast(umurx as integer) as umurx
                        , indikator2
                        , qty
                FROM ('.$sql.') x
                LEFT JOIN "RT" r ON r.id_rt=x.id_rt
                LEFT JOIN "RW" s ON s.id_rw=x.id_rw
                LEFT JOIN "Kelurahan" t ON t.id_kelurahan=x.id_kelurahan
                LEFT JOIN "Kecamatan" u ON u.id_kecamatan=x.id_kecamatan
                LEFT JOIN "Kabupaten" v ON v.id_kabupaten=x.id_kabupaten
                LEFT JOIN "Provinsi" w ON w.id_provinsi=x.id_provinsi';


        switch (request()->input('Indikator')) {
          case 5:
          $sql = '';
          $sql = ' select * from (select  x.id_kelurahan
                , \'Jawaban\' as indikator
                                , x."Answer" as indikator2
                , r.nama_rt
                , s.nama_rw
                , t.nama_kelurahan
                , u.nama_kecamatan
                , v.nama_kabupaten
                , w.nama_provinsi
                                , cast(x.id_rt as integer) as id_rt
                , cast("id_answer" as integer) as "Code"
                , cast("id_answer" as integer) as "umurx"
                , CONCAT("id_answer",\'-\', x."Answer") as minggus
                                , count(qty) as qty
                FROM ( select x.id_provinsi, x.id_kabupaten, x.id_kelurahan, x.id_rw, x.id_rt, x.id_kecamatan,
                          x.id_frm, x.nama_anggotakel, x.jenis_kelamin, x.sts_hubungan
                      , x.sts_kawin, x.usia_kawin, x.id_kb, x.create_by, x.id_answer, x."Answer"
                                             , count(1) qty from (select distinct
                       b.id_propinsi as id_provinsi, b.id_kabupaten, b.id_desa as id_kelurahan, b.id_rw, b.id_rt, b.id_kecamatan,
                             a.id_frm, a.nama_anggotakel, a.jenis_kelamin, a.sts_hubungan
                       , a.sts_kawin, a.usia_kawin, c.id_kb, coalesce(d.id_answer,\'0\') as id_answer, b.create_by,
                             case when  coalesce(d.id_answer,\'0\') = \'0\' then \'Tidak Jawab\' when coalesce(d.id_answer,\'0\') = \'1\' then \'Ya\' else \'Tidak\' end as "Answer",
                             count(1) qty
                FROM mst_formulir_dtl a
                join mst_formulir b on  a.id_frm = b.id_frm AND periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\')
                left join frm_kb c on c.id_frm = a.id_frm and c.id_kb = \'4\'
                left join frm_kb_answer d on d.id_frm = a.id_frm and c.id_kb = \'4\' and d.id_frm = c.id_frm and d.id_kb = c.id_kb
                where a.sts_hubungan = 2 and a.jenis_kelamin = \'2\'
                group by a.id_frm, a.nama_anggotakel, a.jenis_kelamin, a.sts_hubungan
                       , a.sts_kawin, a.usia_kawin, c.id_kb, b.create_by,
                             d.id_answer, b.id_propinsi, b.id_kabupaten, b.id_desa, b.id_rw, b.id_rt, b.id_kecamatan
                order by c.id_kb, a.id_frm, coalesce(d.id_answer,\'0\'), a.nama_anggotakel, a.jenis_kelamin, a.sts_hubungan
                       , a.sts_kawin, a.usia_kawin) x
                 group by x.id_provinsi, x.id_kabupaten, x.id_kelurahan, x.id_rw, x.id_rt, x.id_kecamatan,
                             x.id_frm, x.nama_anggotakel, x.jenis_kelamin, x.sts_hubungan
                        , x.sts_kawin, x.usia_kawin, x.id_kb, x.create_by,x.id_answer ,x."Answer" ) x
                LEFT JOIN "RT" r ON r.id_rt=x.id_rt
                LEFT JOIN "RW" s ON s.id_rw=x.id_rw
                LEFT JOIN "Kelurahan" t ON t.id_kelurahan=x.id_kelurahan
                LEFT JOIN "Kecamatan" u ON u.id_kecamatan=x.id_kecamatan
                LEFT JOIN "Kabupaten" v ON v.id_kabupaten=x.id_kabupaten
                LEFT JOIN "Provinsi" w ON w.id_provinsi=x.id_provinsi
                group by x.id_kelurahan
                                , x."Answer"
                , r.nama_rt
                , s.nama_rw
                , t.nama_kelurahan
                , u.nama_kecamatan
                , v.nama_kabupaten
                , w.nama_provinsi
                                , cast(x.id_rt as integer)
                , cast("id_answer" as integer) ) x';
          break;
        }

        if (!empty(request()->input('RT'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
        } elseif (!empty(request()->input('RW'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
        } elseif (!empty(request()->input('Kelurahan'))) {
            $sql = 'SELECT * FROM ('.$sql.') x WHERE id_kelurahan='.request()->input('Kelurahan').' ';
        }


        if (request()->input('print')==1) {
            if (!empty(request()->input('RT'))) {
                $nama_wilayah = \DB::table('v_rt')->where('id_rt', request()->input('RT'))->pluck('fullwilayahrev')->first();
            } elseif (!empty(request()->input('RW'))) {
                $nama_wilayah = \DB::table('v_rw')->where('id_rw', request()->input('RW'))->pluck('fullwilayahrev')->first();
            } elseif (!empty(request()->input('Kelurahan'))) {
                $nama_wilayah = \DB::table('v_kelurahan')->where('id_kelurahan', request()->input('Kelurahan'))->pluck('fullwilayahrev')->first();
            } else {
                $allkel = \DB::table('v_kelurahan')->whereIn('id_kelurahan', $wilayah->pluck('id'))->pluck('fullwilayahrev');
                $nama_wilayah = '';
                foreach ($allkel as $kel) {
                    $nama_wilayah .= $kel. '<br>' ;
                };
            }

            $sql = $sql.' order by cast(x.id_rt as integer), cast("Code" as integer) , cast(umurx as integer) ';
            $rows = \DB::select($sql);

            //return view('laporan.target_pdf_anomali', ['rows'=>$rows, 'nama_wilayah'=>$nama_wilayah]);
            $pdf = PDF::loadview('laporan.target_pdf_anomali', ['rows'=>$rows, 'nama_wilayah'=>$nama_wilayah, 'indikators'=>request()->input('Indikator')]);
            return $pdf->stream();
        } else {
            $data = new jqGrid($sql, ['searchFields'=>['UserName', 'NamaLengkap']]);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }
}
