<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Laporan\StatusValid;
use App\Models\User;
use App\Models\Anomali;
use App\Models\UserAkses;

use App\Libraries\DataTable;
use App\Libraries\jqGrid;

use DB;
use PDF;

class RekapitulasiController extends Controller
{
    public function index()
    {
        $userID = currentUser('ID');
        $indikator = \DB::table('Parameter')->where('Group', 'Anomali')->get();
        $periode = \DB::table('PeriodeSensus')->orderBy('Tahun', 'DESC')->get();
        $valperiode = currentUser('PeriodeSensus');
        $roleId = currentUser('RoleID');
        $userAkses = UserAkses::where('UserID', $userID)->get()->pluck('WilayahID')->toArray();
        $valwilayah = count($userAkses)==1 ? $userAkses[0] : null;
        if (in_array(currentUser('RoleID'), [2,3])) { // check RoleID
            $valperiode = null;
            $valwilayah = null;
        }

        

        $sqlApprove = '';
        $sqlApprove = 'select distinct "ID_Kecamatan" 
                         from "App_Rekap_Wilayah_Kec"
                        WHERE "ID_Kecamatan"  IN ('.implode(',', $userAkses).') 
                          and "Status_Approve_Kec" limit 1';

        $pass = '0';
        $rows = \DB::select($sqlApprove);
        if (count($rows) > 0) {
            $pass = '1';
        }

        //debug($pass); exit;
            
        $model = new \App\Models\Master\Kelurahan();        

        $wilayah = $model->getByUserID();

        $whereIn = implode(',', $wilayah->pluck('id')->toArray());

        $sql = 'select distinct id_provinsi, nama_provinsi, id_kabupaten, nama_kabupaten, id_kecamatan, nama_kecamatan from v_data_wilayah_all  x WHERE id_kelurahan IN ('.$whereIn.') limit 1';

        $cakupan = \DB::select($sql);
        //debug($cakupan); exit;

        //$userPendata = $this->userPendata($userID);
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        return view('laporan.rekapitulasi')->with(compact('indikator', 'periode', 'valperiode', 'wilayah', 'valwilayah', 'userPendata', 'cakupan', 'pass'));
        ;
    }

    public function data()
    {
        $result = [];
        $request = request()->all();


        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);

        $model = new \App\Models\Master\Kelurahan();
        $modelRW = new \App\Models\Master\RW(); 
        $modelRT = new \App\Models\Master\RT(); 

        $wilayah = $model->getByUserID();
           
        if (request()->input('JenisData')==1) {

            if (!empty(request()->input('Kelurahan'))) {
                //$whereIn = implode(',', request()->input('Kelurahan'));
                //debug(implode(',', (array)request()->input('Kelurahan'))); exit;
                $whereIn = implode(',', (array)request()->input('Kelurahan'));
            } else {
                $whereIn = implode(',', $wilayah->pluck('id')->toArray());
            }
        } elseif (request()->input('JenisData')==2) { 
            if (!empty(request()->input('RW'))) {
                $whereIn = implode(',', (array)request()->input('RW'));
            } else {
                if (!empty(request()->input('Kelurahan'))) {
                        $result = $modelRW->getByParentss(request()->input('Kelurahan'));                        
                        $whereIn = implode(',',  $result);
                } else { // Jika Kelurahan Dikosongkan
                        $result = $modelRW->getByParentss($wilayah->pluck('id')->toArray());                        
                        $whereIn = implode(',',  $result);
                }
            }
        } else {            

            if (!empty(request()->input('RT'))) {
                $whereIn = implode(',', (array)request()->input('RT'));
            } else {
                if (!empty(request()->input('Kelurahan'))) {   
                    if (!empty(request()->input('RW'))) { 
                        //echo '1';
                        $result = $modelRT->getByParentAll(request()->input('RW'));
                        $whereIn = implode(',',  $result);
                    } else { // RW Kosong
                        //echo '2';
                        $AllRW = $modelRW->getByParentss(request()->input('Kelurahan'));         
                        $result = $modelRT->getByParentAll($AllRW);
                        $whereIn = implode(',',  $result);
                    }
                } else {
                    //echo '3';
                    $AllRW = $modelRW->getByParentss($wilayah->pluck('id')->toArray());         
                    $result = $modelRT->getByParentAll($AllRW);
                    $whereIn = implode(',',  $result);                    
                }                
            }
        }
        
        switch (request()->input('JenisData')) {
            case 1:
               $sql = 'select c.nama_kelurahan as nama, c.id_kelurahan as kode
                         , count(cond1.id_frm) as Jml_PUS
                         , count(cond2.id_frm) as Jml_PUS_KB
                         , count(cond3.id_frm) as Jml_PUS_NonKB
                         , count(cond4.id_frm) as Jml_PUS_Hamil    
                         , sum(coalesce("Target_KK",0)) as "Target_KK"                       
                from mst_formulir b
                 join v_kelurahan c on b.id_desa = c.id_kelurahan
                 left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                 left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                               and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                               and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm   
                left join ( select id_kelurahan, "Target_KK" from v_target_kk_periode ) as cond5 on cond5.id_kelurahan = b.id_desa               
                where periode_sensus=\''.request()->input('PeriodeSensus').'\'  and b.create_by IN (\''.$ids.'\')
                and b.id_desa in ('.$whereIn.')                             
                group by c.nama_kelurahan, c.id_kelurahan';

                break;
            case 2:
                $sql = 'select c.nama_rw as nama, c.id_rw as kode
                         , count(cond1.id_frm) as Jml_PUS
                         , count(cond2.id_frm) as Jml_PUS_KB
                         , count(cond3.id_frm) as Jml_PUS_NonKB
                         , count(cond4.id_frm) as Jml_PUS_Hamil
                         , sum(coalesce("Target_KK",0)) as "Target_KK"  
                from mst_formulir b
                 join v_rw c on b.id_rw = c.id_rw
                 left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                 left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                               and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                               and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm 
                left join ( select id_kelurahan, "Target_KK" from v_target_kk_periode ) as cond5 on cond5.id_kelurahan = b.id_desa                                 
                where periode_sensus=\''.request()->input('PeriodeSensus').'\'   and b.create_by IN (\''.$ids.'\')  
                and b.id_rw in ('.$whereIn.')                           
                group by c.nama_rw, c.id_rw';
                break;
            case 3:
               $sql = 'select null id_frm, c.nama_rt as nama, c.id_rt as kode
                         , count(cond1.id_frm) as Jml_PUS
                         , count(cond2.id_frm) as Jml_PUS_KB
                         , count(cond3.id_frm) as Jml_PUS_NonKB
                         , count(cond4.id_frm) as Jml_PUS_Hamil
                         , sum(coalesce("Target_KK",0)) as "Target_KK"  
                 from mst_formulir b
                 join v_rt c on b.id_rt = c.id_rt
                 left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                 left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                               and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                               and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm  
                left join ( select id_kelurahan, "Target_KK" from v_target_kk_periode ) as cond5 on cond5.id_kelurahan = b.id_desa 
                where periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\')    
                and b.id_rt in ('.$whereIn.')                           
                group by c.nama_rt, c.id_rt';
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
            

            $print_id = implode(',', request()->input('print_id'));
            
            // $m = new \App\Models\Master\RT();
            // $wilayah = $m->distinct()->whereIn('KelurahanID', request()->input('print_id'))->get(['KelurahanID', 'NamaKelurahan', 'KecamatanID', 'NamaKecamatan', 'KabupatenID', 'NamaKabupaten', 'ProvinsiID', 'NamaProvinsi']);
            // debug($wilayah);
            $sql = 'SELECT * FROM ('.$sql.') x WHERE kode IN ('.$print_id.')'; 
            $rows = \DB::select($sql);
            // debug($rows);
            // $sql = $sql.' order by cast(x.id_rt as integer), cast("Code" as integer) , cast(umurx as integer) ';

            //return view('laporan.target_pdf_anomali', ['rows'=>$rows, 'nama_wilayah'=>$nama_wilayah]);
            $pdf = PDF::loadview('laporan.rekapitulasi_desa_pdf', compact('rows'));
            return $pdf->stream('Rekapitulasi_Desa.pdf');
        } else {
        

            //$sql = 'SELECT * FROM ('.$sql.') x WHERE kode IN ('.$whereIn.')'; 
            //debug($sql); exit;
            $data = new DataTable($sql, ['searchFields'=>['id_frm', 'nama', 'id_rt', 'nama_rt']]);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }


    

    public function cetak()
    {
        $result = [];
        $request = request()->all();
        $print_id = request()->input('print_id');

        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        $childUser = array();
        foreach ($userPendata as $value) {
            array_push($childUser, $value->UserName);
        }

        $ids = join("','", $childUser);
        $jenisRekap = $request['JenisData']+1;
        switch ($jenisRekap) {
            case 1:
               $sql = 'select c.nama_kelurahan as nama, c.id_kelurahan as kode
                        , c.nama_kelurahan, c.nama_kecamatan, c.nama_kabupaten, c.nama_provinsi
                         , count(cond1.id_frm) as Jml_PUS
                         , count(cond2.id_frm) as Jml_PUS_KB
                         , count(cond3.id_frm) as Jml_PUS_NonKB
                         , count(cond4.id_frm) as Jml_PUS_Hamil    
                         , sum(coalesce("Target_KK",0)) as "Target_KK"                       
                from mst_formulir b
                 join v_kelurahan c on b.id_desa = c.id_kelurahan
                 left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                 left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                               and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                               and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm   
                left join ( select id_kelurahan, "Target_KK" from v_target_kk_periode ) as cond5 on cond5.id_kelurahan = b.id_desa               
                where periode_sensus=\''.request()->input('PeriodeSensus').'\'  and b.create_by IN (\''.$ids.'\')                             
                group by c.nama_kelurahan, c.id_kelurahan, c.nama_kecamatan, c.nama_kabupaten, c.nama_provinsi';

                break;
            case 2:
                $sql = 'select c.nama_rw as nama, c.id_rw as kode
                        , c.id_rw, c.nama_rw , c.id_kelurahan, c.nama_kelurahan, c.id_kecamatan, c.nama_kecamatan, c.id_kabupaten, c.nama_kabupaten, c.id_provinsi, c.nama_provinsi
                         , count(cond1.id_frm) as Jml_PUS
                         , count(cond2.id_frm) as Jml_PUS_KB
                         , count(cond3.id_frm) as Jml_PUS_NonKB
                         , count(cond4.id_frm) as Jml_PUS_Hamil
                         , sum(coalesce("Target_KK",0)) as "Target_KK"  
                from mst_formulir b
                 join v_rw c on b.id_rw = c.id_rw
                 left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                 left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                               and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                               and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm 
                left join ( select id_kelurahan, "Target_KK" from v_target_kk_periode ) as cond5 on cond5.id_kelurahan = b.id_desa                                 
                where periode_sensus=\''.request()->input('PeriodeSensus').'\'   and b.create_by IN (\''.$ids.'\')                            
                group by c.nama_rw, c.id_rw, c.id_kelurahan, c.nama_kelurahan, c.id_kecamatan, c.nama_kecamatan, c.id_kabupaten, c.nama_kabupaten, c.id_provinsi, c.nama_provinsi';

                $modelRW = new \App\Models\Master\RW(); 
                $kode_rw = $modelRW->getByParent($print_id)->pluck('id')->toArray();
                $whereIn = implode(',', $kode_rw);
                $sql = 'SELECT * FROM ('.$sql.') x WHERE kode IN ('.$whereIn.')'; 
                $rows = DB::select($sql);
                $aggrows = collect($rows)->groupby('id_kelurahan');
                // debug($aggrows);exit;
                $pdf = PDF::loadview('laporan.rekapitulasi_desa_pdf', compact('aggrows'));
                return $pdf->stream('Rekapitulasi_Desa.pdf');
                break;
            case 3:
               $sql = 'select null id_frm, c.nama_rt as nama, c.id_rt as kode
               ,c.id_rt, c.nama_rt, c.id_rw, c.nama_rw , c.id_kelurahan, c.nama_kelurahan, c.id_kecamatan, c.nama_kecamatan, c.id_kabupaten, c.nama_kabupaten, c.id_provinsi, c.nama_provinsi
                         , count(cond1.id_frm) as Jml_PUS
                         , count(cond2.id_frm) as Jml_PUS_KB
                         , count(cond3.id_frm) as Jml_PUS_NonKB
                         , count(cond4.id_frm) as Jml_PUS_Hamil
                         , sum(coalesce("Target_KK",0)) as "Target_KK"  
                 from mst_formulir b
                 join v_rt c on b.id_rt = c.id_rt
                 left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                 left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                               and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                               and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm  
                left join ( select id_kelurahan, "Target_KK" from v_target_kk_periode ) as cond5 on cond5.id_kelurahan = b.id_desa 
                where periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\')                              
                group by c.nama_rt, c.id_rt
                , c.id_rw, c.nama_rw , c.id_kelurahan, c.nama_kelurahan, c.id_kecamatan, c.nama_kecamatan, c.id_kabupaten, c.nama_kabupaten, c.id_provinsi, c.nama_provinsi';

                $modelRT = new \App\Models\Master\RT(); 
                $kode_rt = $modelRT->getByParent($print_id)->pluck('id')->toArray();
                $whereIn = implode(',', $kode_rt);
                $sql = 'SELECT * FROM ('.$sql.') x WHERE kode IN ('.$whereIn.')'; 
                $rows = DB::select($sql);
                $aggrows = collect($rows)->groupby('id_rw');
                $pdf = PDF::loadview('laporan.rekapitulasi_rw_pdf', compact('aggrows'));
                return $pdf->stream('Rekapitulasi_RW.pdf');
                break;

            case 4:
                   $sql = 'select null id_frm, c.nama_rt as nama, c.id_rt as kode
                   ,c.id_rt, c.nama_rt, c.id_rw, c.nama_rw , c.id_kelurahan, c.nama_kelurahan, c.id_kecamatan, c.nama_kecamatan, c.id_kabupaten, c.nama_kabupaten, c.id_provinsi, c.nama_provinsi
                             , count(cond1.id_frm) as Jml_PUS
                             , count(cond2.id_frm) as Jml_PUS_KB
                             , count(cond3.id_frm) as Jml_PUS_NonKB
                             , count(cond4.id_frm) as Jml_PUS_Hamil
                             , sum(coalesce("Target_KK",0)) as "Target_KK"  
                     from mst_formulir b
                     join v_rt c on b.id_rt = c.id_rt
                     left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                                  where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                    and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                     left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                                  where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                    and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                    and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                    and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                    left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                                 where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                   and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                   and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                                   and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                    left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                                 where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                   and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                   and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                                   and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm  
                    left join ( select id_kelurahan, "Target_KK" from v_target_kk_periode ) as cond5 on cond5.id_kelurahan = b.id_desa 
                    where periode_sensus=\''.request()->input('PeriodeSensus').'\' and b.create_by IN (\''.$ids.'\')                              
                    group by c.nama_rt, c.id_rt
                    , c.id_rw, c.nama_rw , c.id_kelurahan, c.nama_kelurahan, c.id_kecamatan, c.nama_kecamatan, c.id_kabupaten, c.nama_kabupaten, c.id_provinsi, c.nama_provinsi';
    
                    // $modelRT = new \App\Models\Master\RT(); 
                    // $kode_rt = $modelRT->getByParent($print_id)->pluck('id')->toArray();
                    $whereIn = implode(',', $print_id);
                    $sql = 'SELECT * FROM ('.$sql.') x WHERE kode IN ('.$whereIn.')'; 
                    $rows = DB::select($sql);
                    $pdf = PDF::loadview('laporan.rekapitulasi_rt_pdf', compact('rows'));
                    return $pdf->stream('Rekapitulasi_RT.pdf');
                    break;
        }
        

            $model = new \App\Models\Master\Kelurahan();
            $modelRW = new \App\Models\Master\RW(); 
            $modelRT = new \App\Models\Master\RT(); 

            $wilayah = $model->getByUserID();
            debug($jenisRekap);
           debug($print_id);
        if ($jenisRekap==1) {
        } elseif ($jenisRekap==2) { 
            if (!empty(request()->input('RW'))) {
                $whereIn = implode(',', (array)request()->input('RW'));
            } else {
                if (!empty(request()->input('Kelurahan'))) {
                        $result = $modelRW->getByParentss(request()->input('Kelurahan'));                        
                        $whereIn = implode(',',  $result);
                } else { // Jika Kelurahan Dikosongkan
                        $result = $modelRW->getByParentss($wilayah->pluck('id')->toArray());                        
                        $whereIn = implode(',',  $result);
                }
            }
        } else {            

            if (!empty(request()->input('RT'))) {
                $whereIn = implode(',', (array)request()->input('RT'));
            } else {
                if (!empty(request()->input('Kelurahan'))) {   
                    if (!empty(request()->input('RW'))) { 
                        //echo '1';
                        $result = $modelRT->getByParentAll(request()->input('RW'));
                        $whereIn = implode(',',  $result);
                    } else { // RW Kosong
                        //echo '2';
                        $AllRW = $modelRW->getByParentss(request()->input('Kelurahan'));         
                        $result = $modelRT->getByParentAll($AllRW);
                        $whereIn = implode(',',  $result);
                    }
                } else {
                    //echo '3';
                    $AllRW = $modelRW->getByParentss($wilayah->pluck('id')->toArray());         
                    $result = $modelRT->getByParentAll($AllRW);
                    $whereIn = implode(',',  $result);                    
                }                
            }
        }
        
        $sql = 'SELECT * FROM ('.$sql.') x WHERE kode IN ('.$whereIn.')'; 
        debug($sql);
        exit;

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
            

            $print_id = implode(',', request()->input('print_id'));
            
            // $m = new \App\Models\Master\RT();
            // $wilayah = $m->distinct()->whereIn('KelurahanID', request()->input('print_id'))->get(['KelurahanID', 'NamaKelurahan', 'KecamatanID', 'NamaKecamatan', 'KabupatenID', 'NamaKabupaten', 'ProvinsiID', 'NamaProvinsi']);
            // debug($wilayah);
            $sql = 'SELECT * FROM ('.$sql.') x WHERE kode IN ('.$print_id.')'; 
            $rows = \DB::select($sql);
            // debug($rows);
            // $sql = $sql.' order by cast(x.id_rt as integer), cast("Code" as integer) , cast(umurx as integer) ';

            //return view('laporan.target_pdf_anomali', ['rows'=>$rows, 'nama_wilayah'=>$nama_wilayah]);
            $pdf = PDF::loadview('laporan.rekapitulasi_desa_pdf', compact('rows'));
            return $pdf->stream('Rekapitulasi_Desa.pdf');
        } else {
        

            
            $data = new DataTable($sql, ['searchFields'=>['id_frm', 'nama', 'id_rt', 'nama_rt']]);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }

    //----
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

        $sql = 'select c.nama_provinsi, c.nama_kabupaten, c.nama_kecamatan
                     , c.nama_kelurahan, c.nama_rw, c.nama_rt
                     , count(cond1.id_frm) as Jml_PUS
                     , count(cond2.id_frm) as Jml_PUS_KB
                     , count(cond3.id_frm) as Jml_PUS_NonKB
                     , count(cond4.id_frm) as Jml_PUS_Hamil
                 from mst_formulir b
                 join v_data_wilayah_all c on b.id_rt = c.id_rt and b.id_rw =c.id_rw
                 left join ( select id_frm, nik,umur  from mst_formulir_dtl a
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\' and usia_kawin BETWEEN 15 and 49    ) as cond1 on cond1.id_frm = b.id_frm
                 left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                              where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                                and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                                and bb.id_kb = \'4\' and bb.varnum1 = \'1\'
                                and a.id_frm = bb.id_frm ) as cond2 on cond2.id_frm = b.id_frm 
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'4\' and bb.varnum1 in (\'0\', \'2\')
                               and a.id_frm = bb.id_frm ) as cond3 on cond3.id_frm = b.id_frm                              
                left join ( select a.id_frm, a.nik, a.umur from mst_formulir_dtl a, frm_kb bb
                             where a.jenis_kelamin = \'2\' and a.sts_hubungan = \'2\' 
                               and a.sts_kawin = \'2\'  and usia_kawin BETWEEN 15 and 49
                               and bb.id_kb = \'3\' and bb.varnum1 in (\'1\')
                               and a.id_frm = bb.id_frm ) as cond4 on cond4.id_frm = b.id_frm                                  
                group by c.nama_provinsi, c.nama_kabupaten, c.nama_kecamatan
                         , c.nama_kelurahan, c.nama_rw, c.nama_rt';

        
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
        
        //debug($sql); exit;

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

    public function validme($id)
    {
        try {
            \DB::table('mst_formulir')->where('id_frm', $id)->update(
                ['status_sensus'    => 1,
                'update_date'       => date('Y-m-d H:i:s'),
                'update_by'         => currentUser('UserName')]
            );

            $result = ['status' => true,'message' => 'Your data has been validate.'];
        } catch (\Exception $e) {
            $result = ['status' => false,'message' => $e];
        }

        return $this->jsonOutput($result);
    }

    public function approve () {

        $periode   = request()->input('PeriodeSensus');
        $kecamatan = request()->input('Kecamatan');

        $sql = 'insert into "App_Rekap_Wilayah_Kec"
                select \''.$periode.'\' , id_provinsi, id_kabupaten, id_kecamatan, true from v_kecamatan
                where id_kecamatan = \''.$kecamatan.'\'
                limit 1';
    
        try {
            \DB::select($sql);  
            $result = ['status' => true,'message' => 'Data Telah Di Approve. Terima Kasih.'];  
        } catch (\Exception $e) {
            $result = ['status' => false,'message' => $e];
        } 

        return $this->jsonOutput($result);      
    }
}
