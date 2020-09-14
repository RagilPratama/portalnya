<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\Models\UserAkses;
use App\Libraries\DataTable;

class PusController extends Controller
{
    public function __construct()
    {
        // $this->middleware('checkPermission:monpus');
    }

    public function index()
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->get();

        $userID = currentUser('ID');
        $userAkses = UserAkses::where('UserID', $userID)->get()->pluck('WilayahID')->toArray();
        $valwilayah = count($userAkses)==1 ? $userAkses[0] : null;
        if (in_array(currentUser('RoleID'), [2,3,6])) { // check RoleID
            $valperiode = null;
            $valwilayah = null;
        }
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        return view('laporan.pus')->with(compact('periode','valwilayah','wilayah'));
    }

    // public function data(Request $request)
    // {
    //     $umur = [
    //         1 => [10,14],
    //         2 => [15,49],
    //         3 => [10,49],
    //     ];
    //     $periode = $request->periode_sensus;
    //     $pregcond = $request->status_hamil==1 ? ' c.id_answer=1 ' : ' c.id_answer=2 ';

    //     $sql = '
    //     SELECT r."NamaKecamatan" as nama_kecamatan, r."NamaKelurahan" as nama_kelurahan, r."NamaRW" as nama_rw, r.nama_rt, x.* 
    //     FROM (
    //     SELECT
    //     a.no_kk
    //     , a.no_urutrmh
    //     , a.no_urutkel
    //     , a.create_by
    //     , b.umur
    //     , b.no_urutnik
    //     , a.id_propinsi
    //     , a.id_kabupaten
    //     , a.id_kecamatan
    //     , a.id_desa
    //     , a.id_rw
    //     , a.id_rt
    //     FROM mst_formulir_dtl b
    //     INNER JOIN mst_formulir a ON a.id_frm=b.id_frm
    //     INNER JOIN frm_kb_answer c ON c.id_frm=a.id_frm AND c.id_kb=3 AND '.$pregcond.'
    //     WHERE a.periode_sensus=\''.$request->periode_sensus.'\' 
    //     AND b.jenis_kelamin=\'2\'
    //     AND b.umur BETWEEN '.$umur[$request->kelompok_umur][0].' AND '.$umur[$request->kelompok_umur][1].'
    //     ) x 
    //     INNER JOIN "RT" r ON r.id_rt=x.id_rt AND r.id_rw=x.id_rw AND r."KelurahanID"=x.id_desa AND r."KecamatanID"=x.id_kecamatan
    //     ';
        
    //     $aksesWilayah  = collect(currentUser('AksesWilayah'))->first();
    //     $cond = ' 1=0 ';
    //     switch (currentUser('TingkatWilayahID')) {
    //         case 3:
    //             $sql .= ' WHERE x.id_kecamatan = '.$aksesWilayah->id_kecamatan.' ';
    //             break;
    //     }

    //     if (request()->input('print')==1) {
    //         if (!empty(request()->input('RT'))) {
    //             $wilrow = DB::table('v_rt')->where('id_rt', request()->input('RT'))->first();
    //         } elseif (!empty(request()->input('RW'))) {
    //             $wilrow = DB::table('v_rw')->where('id_rw', request()->input('RW'))->first();
    //         } elseif (!empty(request()->input('Kelurahan'))) {
    //             $wilrow = DB::table('v_kelurahan')->where('id_kelurahan', request()->input('Kelurahan'))->first();
    //         } else {
    //             $wilrow = collect(currentUser('AksesWilayah'))->first();
    //         }
    //         $rows = DB::select($sql);
    //         $pdf = PDF::loadview('laporan.pus_pdf', compact('rows', 'periode', 'wilrow'));
    //         return $pdf->stream('MonPUS.pdf');
    //     } else {
    //         $data = new DataTable($sql, ['searchFields'=>['nama_kecamatan', 'nama_kelurahan', 'nama_rw', 'nama_rt', 'no_kk']]);
    //         $result = $data->get();
    //         return $this->jsonOutput($result);
    //     }
    // }
    public function data(Request $request)
    {
        $umur = [
            1 => [10,14],
            2 => [15,49],
            3 => [10,49],
        ];
        $periode = $request->periode_sensus;
        
        //Query Hamil
        if($request->status_hamil==1){
            $pregcond = 'AND sh.id_answer =  1';
        }elseif($request->status_hamil==0){
            $pregcond = 'AND sh.id_answer =  2';
        }else{
            $pregcond = ' ';
        }

        //Query Kelurahan
        if($request->kelurahan){
            $kelurahan = 'AND b.id_desa = \''.$request->kelurahan.'\'';
        }else{
            $kelurahan = ' ';
        }

        //Query RT
        if($request->rw){
            $rw = 'AND b.id_rw = \''.$request->rw.'\'';
        }else{
            $rw = ' ';
        }
        if($request->rt){
            $rt = 'AND b.id_rt = \''.$request->rt.'\'';
        }else{
            $rt = ' ';
        }

        $sql = '
        SELECT b.no_kk, nk.nama_anggotakel as nama_kk, ni.nama_anggotakel as nama_istri, ni.umur as umur_istri, 
        case 
            when sh.id_answer = 1 
            then \'Iya\'
            when sh.id_answer = 2
            then \'Tidak\'
            else null 
        end as status_hamil, 
        skb.id_answer as status_kb,
        case 
            when jkb.id_answer = 1 
            then \'MOW/ Steril Wanita\'
            when jkb.id_answer = 2
            then \'MOP/ Steril Pria\'
            when jkb.id_answer = 3
            then \'IUD/ Spiral/ AKDR\'
            when jkb.id_answer = 4
            then \'Implant/ Susuk\'
            when jkb.id_answer = 5
            then \'Suntik\'
            when jkb.id_answer = 6
            then \'PIL\'
            when jkb.id_answer = 7
            then \'Kondom\'
            when jkb.id_answer = 8
            then \'Mal\'
            when jkb.id_answer = 9
            then \'Tradisional\'
            else null 
        end as jenis_kb,
        b.id_propinsi as propinsi,b.id_kabupaten as kabupaten,b.id_kecamatan as kecamatan,b.id_desa as desa
        from (select * from mst_formulir_dtl WHERE sts_hubungan=1) as nk
        inner join (SELECT * from mst_formulir_dtl WHERE sts_hubungan=2) as ni on (ni.id_frm=nk.id_frm)
        inner join mst_formulir as b on nk.id_frm=b.id_frm
        inner join (SELECT * from frm_kb_answer where id_kb=3) as sh on sh.id_frm=b.id_frm 
        inner join (SELECT * from frm_kb_answer where id_kb=4) as skb on skb.id_frm=b.id_frm 
        inner join (SELECT * from frm_kb_answer where id_kb=7) as jkb on jkb.id_frm=b.id_frm
        WHERE b.periode_sensus=\''.$request->periode_sensus.'\'
        '.$kelurahan.'
        '.$rw.'
        '.$rt.'
        AND ni.umur BETWEEN '.$umur[$request->kelompok_umur][0].' AND '.$umur[$request->kelompok_umur][1].'
        '.$pregcond.'
        ';
        
        // $aksesWilayah  = collect(currentUser('AksesWilayah'))->first();
        // $cond = ' 1=0 ';
        // switch (currentUser('TingkatWilayahID')) {
        //     case 3:
        //         $sql .= ' WHERE x.id_kecamatan = '.$aksesWilayah->id_kecamatan.' ';
        //         break;
        // }

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
            $pdf = PDF::loadview('laporan.pus_pdf', compact('rows', 'periode', 'wilrow'));
            return $pdf->stream('MonPUS.pdf');
        } else {
            $data = new DataTable($sql, ['searchFields'=>['no_kk']]);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }
}
