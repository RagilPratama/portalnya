<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAkses;
use App\Models\User;
use App\Models\Master\Provinsi;
use App\Models\Master\Kabupaten;
use App\Models\Master\Kecamatan;
use App\Models\Master\Kelurahan;
use App\Models\Master\RW;
use App\Models\Master\RT;
use App\Models\Response;

class UserAksesController extends Controller
{
    public function getDDWilayah(Request $request)
    {
        // $ddlevel = $request->ddlevel ?? 1;
        $wid = $request->wid ?? currentUser('akses')[0]['WilayahID'];
        $tkWilayah = $request->tk ?? currentUser('TingkatWilayahID');
        $tkuser = empty($request->uid) ? 0 : User::find($request->uid)->TingkatWilayahID;
        $firstlvl = empty($request->wid) || $tkWilayah==currentUser('TingkatWilayahID') ? true : false ;

        $data = null;
        if ($tkWilayah==1) {
            $m = new Provinsi();
            // $data = $ddlevel==1 ? $m->getByID($wid) : $m->getByParent($wid);
        } elseif ($tkWilayah==2) {
            $m = new Kabupaten();
            // $data = $ddlevel==1 ? $m->getByID($wid) : $m->getByParent($wid);
        } elseif ($tkWilayah==3) {
            $m = new Kecamatan();
            // $data = $ddlevel==1 ? $m->getByID($wid) : $m->getByParent($wid);
        } elseif ($tkWilayah==4) {
            $m = new Kelurahan();
            // $data = $ddlevel==1 ? $m->getByID($wid) : $m->getByParent($wid);
        } elseif ($tkWilayah==5) {
            $m = new RW();
            // $data = $ddlevel==1 ? $m->getByID($wid) : $m->getByParent($wid);
        } elseif ($tkWilayah==6) {
            $m = new RT();
            // $data = $ddlevel==1 ? $m->getByID($wid) : $m->getByParent($wid);
        }
        $data = $firstlvl ? $m->getByID($wid) : $m->getByParent($wid);
        $databaru = [];
        if ($tkWilayah==$tkuser) {
            $usedWilayah = UserAkses::whereIn('WilayahID', $data->pluck('id'))->where('UserID', '!=', $request->uid)->pluck('WilayahID')->toArray();
            foreach ($data as $item) {
                $struct = $item;
                if (in_array($item['id'], $usedWilayah)) $struct['used'] = true;
                $databaru[] = $struct;
            }
        } else {
            
            $databaru = $data;
        }
        $result = [
            // 'ddtkuser' => $tkuser,
            'ddtk' => $tkWilayah,
            'ddnext' => $tkWilayah==$tkuser ? false : true,
            'ddoptions' => $databaru,
        ];

        return response()->json($result);

        
        $result = [
            'ddlevel' => $ddlevel,
            'ddtk' => $tkWilayah,
            'ddnext' => $tkWilayah==$tkuser ? false : true,
            'ddoptions' => $databaru,
        ];
        return $result;
    }

    public function getPathUser($id)
    {
        $m = User::find($id)->AksesWilayah;
        $path = null;
        if (!empty($m)) {
            $path = [
                1 => $m->pluck('id_provinsi')->unique(),
                2 => $m->pluck('id_kabupaten')->unique(),
                3 => $m->pluck('id_kecamatan')->unique(),
                4 => $m->pluck('id_kelurahan')->unique(),
                5 => $m->pluck('id_rw')->unique(),
                6 => $m->pluck('id_rt')->unique(),
            ];
        }
        // debug(($m->pluck('id_rt')->toArray()));
        return $this->jsonOutput($path);
    }

    public function update(Request $request)
    {
        ini_set('max_execution_time', 0); 
        $model = new UserAkses();
        $rows = $model->postUpdate($request);
        return $this->jsonOutput($rows);
    }
}
