<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'v_kelurahan';
    protected $primaryKey = 'id_kelurahan';
    
    public function getByID($id=null)
    {
        $rows = $this->select('id_kelurahan as id', 'nama_kelurahan as text', 'id_kecamatan as id_parent');
        
        if (!empty($id)) {
            if (is_array($id)) {
                $rows = $rows->whereIn('id_kelurahan', $id);
            } else {
                $rows = $rows->where('id_kelurahan', $id);
            }
        } else {
            $rows = $rows->take(1000);
        }
        $rows = $rows->orderBy('nama_kelurahan')->get();
        return $rows;
    }
    
    public function getByParent($id=null)
    {
        $rows = $this->select('id_kelurahan as id', 'nama_kelurahan as text', 'id_kecamatan as id_parent');
        
        if (!empty($id)) {
            if (is_array($id)) {
                $rows = $rows->whereIn('id_kecamatan', $id);
            } else {
                $rows = $rows->where('id_kecamatan', $id);
            }
        } else {
            $rows = $rows->take(1000);
        }
        $rows = $rows->orderBy('nama_kelurahan')->get();
        return $rows;
    }
    
    public function getByKecamatan($id=null)
    {
        $rows = $this->getByParent($id);
        return $rows;
    }
    
    public function getByKabupaten($id=null)
    {
        $rows = $this->select('id_kelurahan as id', 'nama_kelurahan as text', 'id_kecamatan as id_parent');
        
        if (!empty($id)) {
            if (is_array($id)) {
                $rows = $rows->whereIn('id_kabupaten', $id);
            } else {
                $rows = $rows->where('id_kabupaten', $id);
            }
        } else {
            $rows = $rows->take(1000);
        }
        $rows = $rows->orderBy('nama_kelurahan')->get();
        return $rows;
    }
    
    public function getByProvinsi($id=null)
    {
        $rows = $this->select('id_kelurahan as id', 'nama_kelurahan as text', 'id_kecamatan as id_parent');
        
        if (!empty($id)) {
            if (is_array($id)) {
                $rows = $rows->whereIn('id_provinsi', $id);
            } else {
                $rows = $rows->where('id_provinsi', $id);
            }
        } else {
            $rows = $rows->take(1000);
        }
        $rows = $rows->orderBy('nama_kelurahan')->get();
        return $rows;
    }
    
    public function getByUserID($userID=null)
    {
        $rows = [];
        $userID = $userID ?? currentUser('ID');
        $user = \App\Models\User::with('akses')->find($userID);
        if (!empty($user->akses)) {
            $userAkses = $user->akses->pluck('WilayahID')->toArray();
            switch($user->TingkatWilayahID) {
                case 1:
                    $rows = $this->getByProvinsi($userAkses);
                    break;
                case 2:
                    $rows = $this->getByKabupaten($userAkses);
                    break;
                case 3:
                    $rows = $this->getByKecamatan($userAkses);
                    break;
                case 4:
                    $rows = $this->getByID($userAkses);
                    break;
            }
        }
        return $rows;
    }
}
