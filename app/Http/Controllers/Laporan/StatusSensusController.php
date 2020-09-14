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

class StatusSensusController extends Controller
{
    public function index()
    {
        $userID = currentUser('ID');
        $statussensus = \DB::table('Parameter')->where('Group','StatusSensus')->whereIn('Code',[1,2,4])->orderBy('Code')->get();
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
        
        $usermodel = new User();
        $userPendata = $usermodel->childPendata();
        return view('laporan.statussensus')->with(compact('statussensus', 'periode', 'valperiode', 'wilayah', 'valwilayah', 'userPendata'));;
    }
    
    public function data()
    {
        $model = new \App\Models\Master\Kelurahan();
        $wilayah = $model->getByUserID();
        $periode = request()->input('PeriodeSensus');
        $whereKel = implode(',',$wilayah->pluck('id')->toArray());
        
        if(request()->input('StatusSensus') == 3){
            $sql = 'SELECT a.*, b."UserName", b."NamaLengkap", c.nama_rt, d.nama_rw, e.nama_kelurahan, p."Value" as alasan_text
            FROM mst_formulir a
            LEFT JOIN "User" b ON b."UserName"=a.create_by
            LEFT JOIN "RT" c ON c.id_rt=a.id_rt
            LEFT JOIN "RW" d ON d.id_rw=a.id_rw
            LEFT JOIN "Kelurahan" e ON e.id_kelurahan=a.id_desa
            LEFT JOIN "Parameter" p ON p."Code" = a.alasan AND p."Group"=\'Alasan NotValid\'
            WHERE a.periode_sensus=\''.$periode.'\' 
            AND a.id_desa IN ('.$whereKel.')
            ';
        }else{
            $sql = 'SELECT a.*, b."UserName", b."NamaLengkap", c.nama_rt, d.nama_rw, e.nama_kelurahan, p."Value" as alasan_text
            FROM mst_formulir a
            LEFT JOIN "User" b ON b."UserName"=a.create_by
            LEFT JOIN "RT" c ON c.id_rt=a.id_rt
            LEFT JOIN "RW" d ON d.id_rw=a.id_rw
            LEFT JOIN "Kelurahan" e ON e.id_kelurahan=a.id_desa
            LEFT JOIN "Parameter" p ON p."Code" = a.alasan AND p."Group"=\'Alasan NotValid\'
            WHERE a.status_sensus=\''.request()->input('StatusSensus').'\' AND a.periode_sensus=\''.$periode.'\' 
            AND a.id_desa IN ('.$whereKel.')
            ';
        }
        

        if (request()->input('JenisData')==1) {
            if (!empty(request()->input('RT'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rt='.request()->input('RT').' ';
            } elseif (!empty(request()->input('RW'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_rw='.request()->input('RW').' ';
            } elseif (!empty(request()->input('Kelurahan'))) {
                $sql = 'SELECT * FROM ('.$sql.') x WHERE id_desa='.request()->input('Kelurahan').' ';
            }
        } else {
            if (!empty(request()->input('Pendata'))) { 
                $sql = 'SELECT * FROM ('.$sql.') x WHERE create_by=\''.request()->input('Pendata').'\'';
            }
        }
        
            // debug($sql);exit;
        if (request()->input('print')==1) {
            
            $status_text = \DB::table('Parameter')->where('Group','StatusSensus')->where('Code',request()->input('StatusSensus'))->pluck('Value')->first();
            
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
            $pdf = PDF::loadview('laporan.status_pdf', compact('rows', 'periode', 'wilrow', 'status_text'));
            return $pdf->stream();
        } else {
            $data = new DataTable($sql, ['searchFields'=>['UserName', 'NamaLengkap']]);
            $result = $data->get();
            return $this->jsonOutput($result);
        }
    }
    
    public function anulir($id)
    {
        $row = \DB::table('mst_formulir')->where('no_kk', $id)->first();
        $jsonReponse = new \App\Models\Response();
        if (!empty($row)) {
            $update = \DB::table('mst_formulir')->where('no_kk', $id)->update([
                'status_sensus' => 4,
                'update_date' => date('Y-m-d H:i:s'),
                'update_by' => currentUser('UserName'),
            ]);
            $jsonReponse->status = true;
            $jsonReponse->message = 'Data berhasil dianulir';
        } else {
            $jsonReponse->message = 'Data tidak ditemukan';
        }
        
        return $this->jsonOutput($jsonReponse->get());
            
    }
    
}
