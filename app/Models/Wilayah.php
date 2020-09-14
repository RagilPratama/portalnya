<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wilayah extends BaseModel
{
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public function getTreeWilayah3()
    {
        $pTingkatUser = request()->input('tingkatUser');
        $pUserID = request()->input('userID');
        $TingkatWilayahID = currentUser('TingkatWilayahID');
        $arrSess = [
            1 => 'id_provinsi',
            2 => 'id_kabupaten',
            3 => 'id_kecamatan',
            4 => 'id_kelurahan',
            5 => 'id_rw',
            6 => 'id_rt'
        ];
        $wilSess = (array)auth()->user()->AksesWilayah[0];
        $id = $wilSess[$arrSess[$TingkatWilayahID]];
        $arrTingkat = [
            1 => 'ProvinsiID',
            2 => 'KabupatenID',
            3 => 'KecamatanID',
            4 => 'KelurahanID',
            5 => 'id_rw',
            6 => 'id_rt'
        ];
        // $periode = DB::table("PeriodeSensus")->where('IsOpen','Y')->orderBy('Tahun','desc')->first()->Tahun ?? 0;
        $m = new \App\Models\Master\RT();
        $rows = DB::table('RT')->where($arrTingkat[$TingkatWilayahID], $id)
            ->get();
        $result = [];
        $this->recAksesWilayah($rows, $result, $TingkatWilayahID, 0, $pTingkatUser, $pUserID);
        return $result;
    }

    public function recAksesWilayah(&$array, &$result, $TingkatWilayahID, $parent, $pTingkatUser, $pUserID) {
        if ($TingkatWilayahID==1) {
            $activeArea = DB::table('Provinsi')->where('IsActive', true)->pluck('id_provinsi');
            $filtered = $array->whereIn('ProvinsiID', $activeArea)->groupBy('ProvinsiID');
            $userAkses = [];
            $usedAkses = [];
            if ($pTingkatUser == $TingkatWilayahID) {
                $modelAkses = new UserAkses();
                $rowAkses = $modelAkses->where('UserID', $pUserID)->get();
                $userAkses = $rowAkses->toArray();
                $usedAkses = $this->getUsedWilayah($filtered, $rowAkses->pluck('WilayahID'), 6);
            }
            
            foreach ($filtered as $key => $item) {
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->ProvinsiID;
                $row['text'] = $item[0]->NamaProvinsi;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('KabupatenID')->count() > 0 ? true : false;
                $row['isLeaf'] = ($pTingkatUser == $TingkatWilayahID) ? true : false;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['Enabled'] = 1;
                if ($pTingkatUser == $TingkatWilayahID) {
                    foreach ($userAkses as $akses){
                        if ($akses['WilayahID'] == $row['WilayahID']) {
                            $row['Flag'] = 1;
                        }
                    }

                    foreach ($usedAkses as $used){
                        if ($used == $row['WilayahID']) {
                            $row['Enabled'] = 0;
                        }
                    }
                }
                $result[] = $row;
                if ($pTingkatUser > $TingkatWilayahID) {
                    $this->recAksesWilayah($array, $result, $TingkatWilayahID+1, $item[0]->ProvinsiID, $pTingkatUser, $pUserID);
                }
            }

        } elseif ($TingkatWilayahID==2) { // Kabupaten
            if ($parent==0) {
                $activeArea = DB::table('Kabupaten')->where('IsActive', true)->pluck('id_kabupaten');
            } else {
                $activeArea = DB::table('Kabupaten')->where('id_provinsi', $parent)->where('IsActive', true)->pluck('id_kabupaten');
            }
            $filtered = $array->whereIn('KabupatenID', $activeArea)->groupBy('KabupatenID');
            $userAkses = [];
            $usedAkses = [];
            if ($pTingkatUser == $TingkatWilayahID) {
                $modelAkses = new UserAkses();
                $rowAkses = $modelAkses->where('UserID', $pUserID)->get();
                $userAkses = $rowAkses->toArray();
                $usedAkses = $this->getUsedWilayah($filtered, $rowAkses->pluck('WilayahID'), 6);
            }
            
            foreach ($filtered as $key => $item) {
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->KabupatenID;
                $row['text'] = $item[0]->NamaKabupaten;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('KecamatanID')->count() > 0 ? true : false;
                $row['isLeaf'] = ($pTingkatUser == $TingkatWilayahID) ? true : false;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['Enabled'] = 1;
                if ($pTingkatUser == $TingkatWilayahID) {
                    foreach ($userAkses as $akses){
                        if ($akses['WilayahID'] == $row['WilayahID']) {
                            $row['Flag'] = 1;
                        }
                    }

                    foreach ($usedAkses as $used){
                        if ($used == $row['WilayahID']) {
                            $row['Enabled'] = 0;
                        }
                    }
                }
                $result[] = $row;
                if ($pTingkatUser > $TingkatWilayahID) {
                    $this->recAksesWilayah($array, $result, $TingkatWilayahID+1, $item[0]->KabupatenID, $pTingkatUser, $pUserID);
                }
            }

        } elseif ($TingkatWilayahID==3) { // Kecamatan
            if ($parent==0) {
                $activeArea = DB::table('Kecamatan')->where('IsActive', true)->pluck('id_kecamatan');
            } else {
                $activeArea = DB::table('Kecamatan')->where('id_kabupaten', $parent)->where('IsActive', true)->pluck('id_kecamatan');
            }
            $filtered = $array->whereIn('KecamatanID', $activeArea)->groupBy('KecamatanID');
            $userAkses = [];
            $usedAkses = [];
            if ($pTingkatUser == $TingkatWilayahID) {
                $modelAkses = new UserAkses();
                $rowAkses = $modelAkses->where('UserID', $pUserID)->get();
                $userAkses = $rowAkses->toArray();
                $usedAkses = $this->getUsedWilayah($filtered, $rowAkses->pluck('WilayahID'), 6);
            }

            foreach ($filtered as $key => $item) {
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->KecamatanID;
                $row['text'] = $item[0]->NamaKecamatan;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('KelurahanID')->count() > 0 ? true : false;
                $row['isLeaf'] = ($pTingkatUser == $TingkatWilayahID) ? true : false;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['Enabled'] = 1;
                if ($pTingkatUser == $TingkatWilayahID) {
                    foreach ($userAkses as $akses){
                        if ($akses['WilayahID'] == $row['WilayahID']) {
                            $row['Flag'] = 1;
                        }
                    }

                    foreach ($usedAkses as $used){
                        if ($used == $row['WilayahID']) {
                            $row['Enabled'] = 0;
                        }
                    }
                }
                $result[] = $row;
                if ($pTingkatUser > $TingkatWilayahID) {
                    $this->recAksesWilayah($array, $result, $TingkatWilayahID+1, $item[0]->KecamatanID, $pTingkatUser, $pUserID);
                }
            }
            
        } elseif ($TingkatWilayahID==4) { //Kelurahan
            if ($parent==0) {
                $activeArea = DB::table('Kelurahan')->where('IsActive', true)->pluck('id_kelurahan');
            } else {
                $activeArea = DB::table('Kelurahan')->where('id_kecamatan', $parent)->where('IsActive', true)->pluck('id_kelurahan');
            }
            $filtered = $array->whereIn('KelurahanID', $activeArea)->groupBy('KelurahanID');
            $userAkses = [];
            $usedAkses = [];
            if ($pTingkatUser == $TingkatWilayahID) {
                $modelAkses = new UserAkses();
                $rowAkses = $modelAkses->where('UserID', $pUserID)->get();
                $userAkses = $rowAkses->toArray();
                $usedAkses = $this->getUsedWilayah($filtered, $rowAkses->pluck('WilayahID'), 6);
            }

            foreach ($filtered as $key => $item) {
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->KelurahanID;
                $row['text'] = $item[0]->NamaKelurahan;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('id_rw')->count() > 0 ? true : false;
                $row['isLeaf'] = ($pTingkatUser == $TingkatWilayahID) ? true : false;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['Enabled'] = 1;
                if ($pTingkatUser == $TingkatWilayahID) {
                    foreach ($userAkses as $akses){
                        if ($akses['WilayahID'] == $row['WilayahID']) {
                            $row['Flag'] = 1;
                        }
                    }

                    foreach ($usedAkses as $used){
                        if ($used == $row['WilayahID']) {
                            $row['Enabled'] = 0;
                        }
                    }
                }
                $result[] = $row;
                if ($pTingkatUser > $TingkatWilayahID) {
                    $this->recAksesWilayah($array, $result, $TingkatWilayahID+1, $item[0]->KelurahanID, $pTingkatUser, $pUserID);
                }
            }

        } elseif ($TingkatWilayahID==5) { // RW
            if ($parent==0) {
                $activeArea = DB::table('RW')->where('IsActive', true)->pluck('id_rw');
            } else {
                $activeArea = DB::table('RW')->where('id_kelurahan', $parent)->where('IsActive', true)->pluck('id_rw');
            }
            $filtered = $array->whereIn('id_rw', $activeArea)->groupBy('id_rw');
            $userAkses = [];
            $usedAkses = [];
            if ($pTingkatUser == $TingkatWilayahID) {
                $modelAkses = new UserAkses();
                $rowAkses = $modelAkses->where('UserID', $pUserID)->get();
                $userAkses = $rowAkses->toArray();
                $usedAkses = $this->getUsedWilayah($filtered, $rowAkses->pluck('WilayahID'), 5);
            }
            foreach ($filtered as $key => $item) {
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->id_rw;
                $row['text'] = $item[0]->NamaRW;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('id_rt')->count() > 0 ? true : false;
                $row['isLeaf'] = ($pTingkatUser == $TingkatWilayahID) ? true : false;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['Enabled'] = 1;
                if ($pTingkatUser == $TingkatWilayahID) {
                    foreach ($userAkses as $akses){
                        if ($akses['WilayahID'] == $row['WilayahID']) {
                            $row['Flag'] = 1;
                        }
                    }

                    foreach ($usedAkses as $used){
                        if ($used == $row['WilayahID']) {
                            $row['Enabled'] = 0;
                        }
                    }
                }
                $result[] = $row;
                if ($pTingkatUser > $TingkatWilayahID) {
                    $this->recAksesWilayah($array, $result, $TingkatWilayahID+1, $item[0]->id_rw, $pTingkatUser, $pUserID);
                }
            }
        } elseif ($TingkatWilayahID==6) { //RT
            if ($parent==0) {
                $activeArea = DB::table('RT')->pluck('id_rt');
            } else {
                $activeArea = DB::table('RT')->where('id_rw', $parent)->pluck('id_rt');
            }
            $filtered = $array->whereIn('id_rt', $activeArea)->groupBy('id_rt');
            $userAkses = [];
            $usedAkses = [];
            if ($pTingkatUser == $TingkatWilayahID) {
                $modelAkses = new UserAkses();
                $rowAkses = $modelAkses->where('UserID', $pUserID)->get();
                $userAkses = $rowAkses->toArray();
                $usedAkses = $this->getUsedWilayah($filtered, $rowAkses->pluck('WilayahID'), 6);
            }
            foreach ($filtered as $key => $item) {
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->id_rt;
                $row['text'] = $item[0]->nama_rt;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $row['isLeaf'] = true;
                $row['expanded'] = false;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['Enabled'] = 1;
                if ($pTingkatUser == $TingkatWilayahID) {
                    foreach ($userAkses as $akses){
                        if ($akses['WilayahID'] == $row['WilayahID']) {
                            $row['Flag'] = 1;
                        }
                    }

                    foreach ($usedAkses as $used){
                        if ($used == $row['WilayahID']) {
                            $row['Enabled'] = 0;
                        }
                    }
                }
                $result[] = $row;
            }
        }
    }

    public function getUsedWilayah($array, $currentuser, $tkwilayah)
    {
        $ids = array_keys($array->toArray());
        $modelAkses = new UserAkses();
        $rows = $modelAkses->select('UserAkses.ID','UserID','WilayahID','TingkatWilayahID')
        ->join('User','User.ID','UserAkses.UserID')
        ->where('TingkatWilayahID','=',$tkwilayah)
        ->whereNotIn('WilayahID',$currentuser)
        ->whereIn('WilayahID',$ids)->get();
        $existing = $rows->pluck('WilayahID')->unique();
        return $existing;
    }

    public function getTreeTarget()
    {
        ini_set('max_execution_time', 0);
        $TingkatWilayahID = currentUser('TingkatWilayahID');
        $arrSess = [
            1 => 'id_provinsi',
            2 => 'id_kabupaten',
            3 => 'id_kecamatan',
            4 => 'id_kelurahan',
            5 => 'id_rw',
            6 => 'id_rt'
        ];
        $wilSess = (array)auth()->user()->AksesWilayah[0];
        $id = $wilSess[$arrSess[$TingkatWilayahID]];
        $arrTingkat = [
            1 => 'ProvinsiID',
            2 => 'KabupatenID',
            3 => 'KecamatanID',
            4 => 'KelurahanID',
            5 => 'id_rw',
            6 => 'id_rt'
        ];
        $periode = DB::table("PeriodeSensus")->where('IsOpen','Y')->orderBy('Tahun','desc')->first()->Tahun ?? 0;
        $m = new \App\Models\Master\RT();
        $rows = DB::table('RT')->where($arrTingkat[$TingkatWilayahID], $id)
            // ->leftJoin('Target_KK','id_rt','ID_RT')
            // ->where('Periode_Sensus', $periode)
            ->leftJoin('Target_KK', function($join) use($periode)
            {
                $join->on('id_rt', '=', 'ID_RT');
                $join->on('Periode_Sensus','=', DB::raw($periode));
            })
            ->get();
        $result = [];
        $this->recWilayah($rows, $result, $TingkatWilayahID);
        return $result;
    }

    public function recWilayah(&$array, &$result, $TingkatWilayahID, $parent=0) {
        // debug($TingkatWilayahID.' '.$id);
        if ($TingkatWilayahID==1) {
            $activeArea = DB::table('Provinsi')->where('IsActive', true)->pluck('id_provinsi');
            $filtered = $array->whereIn('ProvinsiID', $activeArea)->groupBy('ProvinsiID');

            foreach ($filtered as $key => $item) {
                // $wil = $item[$key];
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->ProvinsiID;
                $row['text'] = $item[0]->NamaProvinsi;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('KabupatenID')->count() > 0 ? true : false;
                $row['isLeaf'] = !$hasChildren;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['TargetKK'] = null;
                $result[] = $row;
                $this->recWilayah($array, $result, $TingkatWilayahID+1, $item[0]->ProvinsiID);
            }

        } elseif ($TingkatWilayahID==2) { // Kabupaten
            if ($parent==0) {
                $activeArea = DB::table('Kabupaten')->where('IsActive', true)->pluck('id_kabupaten');
            } else {
                $activeArea = DB::table('Kabupaten')->where('id_provinsi', $parent)->where('IsActive', true)->pluck('id_kabupaten');
            }
            $filtered = $array->whereIn('KabupatenID', $activeArea)->groupBy('KabupatenID');
            
            foreach ($filtered as $key => $item) {
                // $wil = $item[$key];
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->KabupatenID;
                $row['text'] = $item[0]->NamaKabupaten;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('KecamatanID')->count() > 0 ? true : false;
                $row['isLeaf'] = !$hasChildren;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['TargetKK'] = null;
                $result[] = $row;
                $this->recWilayah($array, $result, $TingkatWilayahID+1, $item[0]->KabupatenID);
            }

        } elseif ($TingkatWilayahID==3) { // Kecamatan
            if ($parent==0) {
                $activeArea = DB::table('Kecamatan')->where('IsActive', true)->pluck('id_kecamatan');
            } else {
                $activeArea = DB::table('Kecamatan')->where('id_kabupaten', $parent)->where('IsActive', true)->pluck('id_kecamatan');
            }
            $filtered = $array->whereIn('KecamatanID', $activeArea)->groupBy('KecamatanID');

            foreach ($filtered as $key => $item) {
                // $wil = $item[$key];
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->KecamatanID;
                $row['text'] = $item[0]->NamaKecamatan;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('KelurahanID')->count() > 0 ? true : false;
                $row['isLeaf'] = !$hasChildren;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['TargetKK'] = null;
                $result[] = $row;
                $this->recWilayah($array, $result, $TingkatWilayahID+1, $item[0]->KecamatanID);
            }
            
        } elseif ($TingkatWilayahID==4) { //Kelurahan
            if ($parent==0) {
                $activeArea = DB::table('Kelurahan')->where('IsActive', true)->pluck('id_kelurahan');
            } else {
                $activeArea = DB::table('Kelurahan')->where('id_kecamatan', $parent)->where('IsActive', true)->pluck('id_kelurahan');
            }
            $filtered = $array->whereIn('KelurahanID', $activeArea)->groupBy('KelurahanID');
            
            foreach ($filtered as $key => $item) {
                // $wil = $item[$key];
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->KelurahanID;
                $row['text'] = $item[0]->NamaKelurahan;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('id_rw')->count() > 0 ? true : false;
                $row['isLeaf'] = !$hasChildren;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['TargetKK'] = null;
                $result[] = $row;
                $this->recWilayah($array, $result, $TingkatWilayahID+1, $item[0]->KelurahanID);
            }

        } elseif ($TingkatWilayahID==5) { // RW
            if ($parent==0) {
                $activeArea = DB::table('RW')->where('IsActive', true)->pluck('id_rw');
            } else {
                $activeArea = DB::table('RW')->where('id_kelurahan', $parent)->where('IsActive', true)->pluck('id_rw');
            }
            $filtered = $array->whereIn('id_rw', $activeArea)->groupBy('id_rw');
            
            foreach ($filtered as $key => $item) {
                // $wil = $item[$key];
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->id_rw;
                $row['text'] = $item[0]->NamaRW;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $hasChildren = $filtered->groupBy('id_rt')->count() > 0 ? true : false;
                $row['isLeaf'] = !$hasChildren;
                $row['expanded'] = $hasChildren;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['TargetKK'] = null;
                $result[] = $row;
                $this->recWilayah($array, $result, $TingkatWilayahID+1, $item[0]->id_rw);
            }
        } elseif ($TingkatWilayahID==6) { //RT
            if ($parent==0) {
                $activeArea = DB::table('RT')->pluck('id_rt');
            } else {
                $activeArea = DB::table('RT')->where('id_rw', $parent)->pluck('id_rt');
            }
            $filtered = $array->whereIn('id_rt', $activeArea)->groupBy('id_rt');

            foreach ($filtered as $key => $item) {
                $row = [];
                $row['id'] = $TingkatWilayahID.'_'.$item[0]->id_rt;
                $row['text'] = $item[0]->nama_rt;
                $row['level'] = $TingkatWilayahID;
                $row['parent'] = ($TingkatWilayahID-1).'_'.$parent;
                $row['isLeaf'] = true;
                $row['expanded'] = false;
                $row['WilayahID'] = $key;
                $row['Flag'] = 0;
                $row['TargetKK'] = $item[0]->Target_KK ?? 0;
                $result[] = $row;
                // $this->recWilayah($array, $result, $TingkatWilayahID+1, $item[0]->id_rw);
            }
        }
    }

    public function getTreeWilayah()
    {
        $tabelWilayah = [
            1 => ['table'=>'Provinsi', 'parent'=>'RegionalID'],
            2 => ['table'=>'Kabupaten', 'parent'=>'id_provinsi'],
            3 => ['table'=>'Kecamatan', 'parent'=>'id_kabupaten'],
            4 => ['table'=>'Kelurahan', 'parent'=>'id_kecamatan'],
            5 => ['table'=>'RW', 'parent'=>'id_kelurahan'],
            6 => ['table'=>'RT', 'parent'=>'id_rw'],
        ];

        $result = [];
        $parentid= request()->input('nodeid') ? explode('_', request()->input('nodeid'))[1] : 0;
        $level= request()->input('n_level') ? request()->input('n_level')+1 : 1;
        $pTingkatUser = request()->input('tingkatUser');
        $userID = request()->input('userID');

        if ($parentid==0) {
            $userID = currentUser('ID');
            $user = User::find($userID);
            $wilayahID = UserAkses::where('UserID', $user->ID)->get()->pluck('WilayahID');
            $level = $user->TingkatWilayahID;
            $modelname = 'App\\Models\\Master\\'.$tabelWilayah[$level]['table'];
            $model = new $modelname;
            $rows = $model->getByID($wilayahID->toArray());
        } else {
            $modelname = 'App\\Models\\Master\\'.$tabelWilayah[$level]['table'];
            $model = new $modelname;
            $rows = $model->getByParent($parentid);
        }
        $userAkses = [];
        if ($level==$pTingkatUser) {
            $modelAkses = new UserAkses();
            $userAkses = $modelAkses->where('UserID', $userID)->get()->toArray();
        }
        foreach ($rows->toArray() as $row) {
            $item = [];
            $item = (array)$row;
            $item['id'] = $level.'_'.$row['id'];
            $item['level'] = $level;
            $item['parent'] = ($level-1).'_'.$parentid;
            $item['isLeaf'] = $level >= $pTingkatUser ? true : false;
            $item['WilayahID'] = $row['id'];
            $item['Flag'] = 0;
            $item['TargetKK'] = null;
            if ($level==$pTingkatUser) {
                foreach ($userAkses as $akses) {
                    if ($akses['WilayahID']==$row['id']) {
                        $item['Flag'] = 1;
                        $item['TargetKK'] = $akses['TargetKK'];
                    }
                }
            }
            $result[] = $item;
        }
        return $result;
    }

    public function getTreeWilayah2()
    {
        $result = [];
        $parentid= request()->input('nodeid') ? explode('_', request()->input('nodeid'))[1] : 0;
        $pTingkatUser = request()->input('tingkatUser');
        $userID = request()->input('userID');

        $this->treeAkses($result, $userID, $pTingkatUser);
        return $result;
    }

    public function treeAkses(&$result, $pUserID, $pTingkatUser, $parentid=0, $level='')
    {
        $tabelWilayah = [
            1 => ['table'=>'Provinsi', 'parent'=>'RegionalID'],
            2 => ['table'=>'Kabupaten', 'parent'=>'id_provinsi'],
            3 => ['table'=>'Kecamatan', 'parent'=>'id_kabupaten'],
            4 => ['table'=>'Kelurahan', 'parent'=>'id_kecamatan'],
            5 => ['table'=>'RW', 'parent'=>'id_kelurahan'],
            6 => ['table'=>'RT', 'parent'=>'id_rw'],
        ];
        if ($level=='') {
            $level = currentUser('TingkatWilayahID');
        }
        if ($level==0) {
            //PUSAT
            $item = [];
            $item['id'] = '0_0';
            $item['text'] = 'PUSAT';
            $item['level'] = $level;
            $item['parent'] = null;
            $item['isLeaf'] = true;
            $item['WilayahID'] = null;
            $item['Flag'] = 0;
            // $item['TargetKK'] = null;
            $result[] = $item;
            $this->treeAkses($result, $pUserID, $pTingkatUser, '*', $level+1);
            $rows = collect([]);
        } else {
            if ($parentid===0) {
                $userID = currentUser('ID');
                $wilayahID = UserAkses::where('UserID', $userID)->pluck('WilayahID');
                $level = currentUser('TingkatWilayahID');
                $modelname = 'App\\Models\\Master\\'.$tabelWilayah[$level]['table'];
                $model = new $modelname;
                $rows = $model->getByID($wilayahID->toArray());
            } else {
                $modelname = 'App\\Models\\Master\\'.$tabelWilayah[$level]['table'];
                $model = new $modelname;
                $rows = $model->getByParent($parentid);
            }
        }
        $userAkses = [];
        if ($level==$pTingkatUser) {
            $modelAkses = new UserAkses();
            $userAkses = $modelAkses->where('UserID', $pUserID)->get()->toArray();
        }
        foreach ($rows->toArray() as $row) {
            $item = [];
            $item = (array)$row;
            $item['id'] = $level.'_'.$row['id'];
            $item['level'] = $level;
            $item['parent'] = ($level-1).'_'.$parentid;
            $haschildren = false;
            if ($level < $pTingkatUser) {
                $childmodelname = 'App\\Models\\Master\\'.$tabelWilayah[$level+1]['table'];
                $childmodel = new $childmodelname;
                $childrows = $childmodel->getByParent($row['id'])->count();
                $haschildren = $childrows > 0 ? true : false;
            }
            $item['isLeaf'] = ($level >= $pTingkatUser || !$haschildren) ? true : false;
            $item['expanded'] = $item['isLeaf'] ? false : true;
            $item['WilayahID'] = $row['id'];
            $item['Flag'] = 0;
            // $item['TargetKK'] = null;
            if ($level==$pTingkatUser) {
                foreach ($userAkses as $akses) {
                    if ($akses['WilayahID']==$row['id']) {
                        $item['Flag'] = 1;
                        // $item['TargetKK'] = $level==5 || $level==6 ? $akses['TargetKK'] : '';
                    }
                }
            }
            $result[] = $item;
            if ($level<$pTingkatUser) {
                $this->treeAkses($result, $pUserID, $pTingkatUser, $row['id'], $level+1);
            }
        }
        return $result;
    }


    public function getProvinces($id)
    {
        //$wilayah = $this->whereRaw('CHAR_LENGTH(kode)=2')->orderBy('nama')->get();
        $sql = 'select * from "Provinsi" limit 100';
        $wilayah = DB::select($sql);
        return $wilayah;
    }


    public function getRegencies($id)
    {
       //$wilayah = $this->where('kode', 'LIKE', $id.'%')->whereRaw('CHAR_LENGTH(kode)=5')->orderBy('nama')->get();
        $sql = 'select id_kabupaten, nama_kabupaten from "Kabupaten" where id_provinsi='.$id.' order by 2 limit 200';
        $wilayah = DB::select($sql);
        return $wilayah;
    }

    public function getDistricts($id)
    {
        //$wilayah = $this->where('kode', 'LIKE', $id.'%')->whereRaw('CHAR_LENGTH(kode)=8')->orderBy('nama')->get();

        $sql = 'select id_kecamatan, nama_kecamatan from "Kecamatan" where id_kabupaten='.$id.' order by 2 limit 300';
        $wilayah = DB::select($sql);
        return $wilayah;
    }

    public function getVillages($id)
    {
        //$wilayah = $this->where('kode', 'LIKE', $id.'%')->whereRaw('CHAR_LENGTH(kode)=13')->orderBy('nama')->get();

        $sql = 'select id_kelurahan, nama_kelurahan from "Kelurahan" where id_kecamatan='.$id.' order by 2 limit 400';
        $wilayah = DB::select($sql);
        return $wilayah;
    }

    public function getDataTable()
    {
        $pegawai = DB::table('mst_formulir_dtl');
        return $pegawai;
    }

    public function getDataProvinsi()
    {
        $sql = 'select * from "Provinsi" limit 100';
        $data = DB::select($sql);
        return $data;
    }

    public function postAddRW()
    {
        $request = request()->all();
        try {
            $maxValue = DB::table('RW')->orderBy('id_rw', 'desc')->value('id_rw');
            $requests['id_rw'] = $maxValue + 1;

            $requests['id_kelurahan'] = $request['idkel'];
            $requests['nama_rw'] = $request['nama_rw'];
            $requests['OriginalNama'] = '-';
            $requests['KodeDepdagri'] = $request['KodeDepdagri'];

            $requests['Created'] = date('Y-m-d H:i:s');
            $requests['CreatedBy'] = currentUser('UserName');
            $requests['LastModified'] = date('Y-m-d H:i:s');
            $requests['LastModifiedBy'] = currentUser('UserName');
            $requests['IsActive'] = 't';

            //$rw = DB::table('RW')->find($id);
            //$rw->fill($requests);
            //$row = $rw->save();
            $rw = DB::table('RW')->insert($requests);

            // send reset password user email
            // $this->sendResetEmail($rw->ID, $request['Password']);

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postEditRW()
    {
        $request = request()->all();
        try {
            $id = $request['id_rw'];
            $requests['nama_rw'] = $request['nama_rw'];
            $requests['LastModified'] = date('Y-m-d H:i:s');
            $requests['LastModifiedBy'] = currentUser('UserName');

            $rw = DB::table('RW')->where('id_rw', $id)->update($requests);

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan, Silahkan Cek Ulang.!';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postAddRT()
    {
        $request = request()->all();

        // cek id depdagri //
        $count = DB::table('RT')->where('id_rw', $request['idRW'])->where('KodeRT', $request['KodeRT'])->count();

        if ($count > 0 ) { 

                $this->jsonResponse->status = false;
                $this->jsonResponse->message = 'Kode RT Sudah Ada Cek Ulang Inputan, Terima kasih.';            
            
        } else {

            try {
                $maxValue = DB::table('RT')->orderBy('id_rt', 'desc')->value('id_rt');
                $maxValue2 = DB::table('RT')->orderBy('RTID', 'desc')->value('RTID');
                $requests['id_rt'] = $maxValue + 1;

                $requests['id_rw'] = $request['idRW'];
                $requests['nama_rt'] = $request['nama_rt'];

                $result = $this->getDataWilayahRW($request['idRW']);
                // loop data header RT -
                foreach ($result as $data) {
                    $requests['KodeRW'] = $data->id_provinsi;
                    $requests['NamaRW'] = $data->nama_rw;
                    $requests['KelurahanID'] = $data->id_kelurahan;
                    $requests['KodeKelurahan'] = $data->KodeDepdagriKelurahan;
                    $requests['NamaKelurahan'] = $data->nama_kelurahan;

                    $requests['KecamatanID'] = $data->id_kecamatan;
                    $requests['KodeKecamatan'] = $data->KodeDepdagriKecamatan;
                    $requests['NamaKecamatan'] = $data->nama_kecamatan;

                    $requests['KabupatenID'] = $data->id_kabupaten;
                    $requests['KodeKabupaten'] = $data->KodeDepdagriKabupaten;
                    $requests['NamaKabupaten'] = $data->nama_kabupaten;

                    $requests['ProvinsiID'] = $data->id_provinsi;
                    $requests['KodeProvinsi'] = $data->KodeDepdagriProvinsi;
                    $requests['NamaProvinsi'] = $data->nama_provinsi;
                }

                $requests['RTID'] = $maxValue2;
                $requests['KodeRT'] = $request['KodeRT'];
                ;

                $rw = DB::table('RT')->insert($requests);

                $this->jsonResponse->status = true;
                $this->jsonResponse->message = 'Data berhasil disimpan';
            } catch (\Exception $e) {
                $this->jsonResponse->message = getExceptionMessage($e);
            }      

        } // end count     
        
        return $this->jsonResponse->get();
    }



    public function postEditRT()
    {
        $request = request()->all();
        try {
            $id = $request['id_rt'];
            $requests['nama_rt'] = $request['nama_rt'];
            //$requests['LastModified'] = date('Y-m-d H:i:s');
            //$requests['LastModifiedBy'] = currentUser('UserName');

            $rw = DB::table('RT')->where('id_rt', $id)->update($requests);

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan, Silahkan Cek Ulang.!';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function deleteEditRT()
    {
        $request = request()->all();
        try {
           
            $id = $request['id_rt'];
            $requests['nama_rt'] = $request['nama_rt'];

            $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
            $count = DB::table('Target_KK')->where('Periode_Sensus', $periode)->where('ID_RT', $id)->count();
            
            if ($count > 0 ) { 

                $this->jsonResponse->status = false;
                $this->jsonResponse->message = 'Masih Ada Target KK yang belum dihapus, Silahkan Cek Ulang.!';            
            
            } else {

                $rw = DB::table('RT')->where('id_rt', $id)->delete();

                $this->jsonResponse->status = true;
                $this->jsonResponse->message = 'Data berhasil dihapus, Silahkan Cek Ulang.!';
            }

        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }


    public function deleteEditRW()
    {
        $request = request()->all();
        try {
           
            $id = $request['id_rw'];
            $requests['nama_rw'] = $request['nama_rw'];

            $count = DB::table('RT')->where('id_rw', $id)->count();
            
            if ($count > 0 ) { 

                $this->jsonResponse->status = false;
                $this->jsonResponse->message = 'Masih Ada RT yang belum dihapus, Silahkan Cek Ulang.!';            
            
            } else {

                $rw = DB::table('RW')->where('id_rw', $id)->delete();

                $this->jsonResponse->status = true;
                $this->jsonResponse->message = 'Data berhasil dihapus, Silahkan Cek Ulang.!';
            }

        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function getDataWilayahRW($idrw)
    {
        $sql = 'select * from v_data_wilayah_rw where id_rw = \''.$idrw.'\' limit 1';

        $result = DB::select($sql);
        //$result = $result->toArray();
        return $result;
    }
    
    public function postTargetKK()
    {
        ini_set('max_execution_time', 0);
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        // $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $aksesWilayah  = collect(currentUser('AksesWilayah'))->first();
        $approved = (new Approval())->isApproved($aksesWilayah->id_kecamatan, 'target');
        if ($approved) {
            $this->jsonResponse->message = 'Data tidak dapat diupdate karena telah dikunci.';
            $this->jsonResponse->data = 'approved';
        } else {
            try {
                $parameter = DB::table('Parameter')->whereIn('Group',['MaxTarget'])->pluck('Value','Code');
                $data = json_decode(request()->post('tree'), true);
                
                /*-- cek target kecamatan --*/
                $maxTargetKecamatan = DB::table('Target_KK_Kecamatan')->where('Periode_Sensus', $periode)->where('ID_Kecamatan', $aksesWilayah->id_kecamatan)->pluck('Target_KK')->first() ?? 0;
                $sumTarget = array_sum(array_column($data, 'TargetKK'));
                if ($maxTargetKecamatan > 0 && $sumTarget > $maxTargetKecamatan) {
                    throw new \Exception('Total Target KK melebihi Target Kecamatan');
                }
                /*-- end cek target kecamatan --*/

                $maxTarget = $parameter['MaxTarget'];
                $maxExceed = false;
                $dataExceed = null;
                $ids = DB::table('RT')->where('KecamatanID', $aksesWilayah->id_kecamatan)->pluck('id_rt');
                DB::transaction(function () use($maxTarget, $data, $ids) {
                    
                    DB::table('Target_KK')->whereIn('ID_RT',$ids)->delete();

                    $periode = DB::table("PeriodeSensus")->where('IsOpen','Y')->orderBy('Tahun','desc')->first()->Tahun ?? 0;

                    $insdata = [];
                    foreach ($data as $item) {
                        $rowdata = [
                            'Periode_Sensus' => $periode,
                            'ID_RT' => $item['WilayahID'],
                            'Target_KK' => $item['TargetKK']
                        ];
                        $insdata[] = $rowdata;
                    }
                    
                    if (!empty($insdata)) DB::table('Target_KK')->insert($insdata);
                    
                });

                DB::transaction(function () use($maxTarget, $data, $ids) {
                    
                    $users = DB::table('User as a')->join('UserAkses as b', 'a.ID','=','b.UserID')
                        ->whereIn('b.WilayahID', $ids)
                        ->whereIn('a.RoleID',[5,6])
                        ->update(['a.LastModified' => date('Y-m-d H:i:s')]);
                });
                
                $this->jsonResponse->status = !$maxExceed;
                $this->jsonResponse->message = $maxExceed ? 'Target KK tidak boleh melebihi batas: '.$maxTarget : 'Data berhasil disimpan';
                $this->jsonResponse->data = $maxExceed ? $dataExceed : null;
            }
            catch (\Exception $ex)
            {
                $this->jsonResponse->message = $ex->getMessage();
            }
        }
        return $this->jsonResponse->get();
    }
    
    public function postTargetKKxxx()
    {
        ini_set('max_execution_time', 0);
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $approved = (new Approval())->isApproved($aksesWilayah->id_kecamatan, 'target');
        if ($approved) {
            $this->jsonResponse->message = 'Data tidak dapat diupdate karena telah dikunci.';
            $this->jsonResponse->data = 'approved';
        } else {
            $parameter = DB::table('Parameter')->whereIn('Group',['MaxTarget'])->pluck('Value','Code');
            $data = json_decode(request()->post('tree'), true);
            $maxTarget = $parameter['MaxTarget'];
            $maxExceed = false;
            $dataExceed = null;
            DB::transaction(function () use(&$maxExceed, &$dataExceed, $maxTarget, $data, $aksesWilayah) {
                
                $ids = DB::table('RT')->where('KecamatanID', $aksesWilayah->id_kecamatan)->pluck('id_rt');
                // DB::table('Target_KK')->whereIn('ID_RT',$ids)->delete();

                $periode = DB::table("PeriodeSensus")->where('IsOpen','Y')->orderBy('Tahun','desc')->first()->Tahun ?? 0;

                $insdata = [];
                foreach ($data as $item) {
                    $rowdata = [
                        'Periode_Sensus' => $periode,
                        'ID_RT' => $item['WilayahID'],
                        'Target_KK' => $item['TargetKK']
                    ];
                    $insdata[] = $rowdata;
                    DB::table('Target_KK')->updateOrInsert(
                        [
                            'Periode_Sensus' => $periode,
                            'ID_RT' => $item['WilayahID']
                        ],
                        [
                            'Target_KK' => $item['TargetKK']
                        ]
                    );
                }
            });
            
            $this->jsonResponse->status = !$maxExceed;
            $this->jsonResponse->message = $maxExceed ? 'Target KK tidak boleh melebihi batas: '.$maxTarget : 'Data berhasil disimpan';
            $this->jsonResponse->data = $maxExceed ? $dataExceed : null;
        }
        return $this->jsonResponse->get();
    }
    
}
