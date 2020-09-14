<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use DB;

class RT extends Model
{
    protected $table = 'RT';
    protected $primaryKey = 'id_rt';
    protected $appends = ['Target_KK'];
    
    public function getTargetKKAttribute($value)
    {
        $id = $this->id_rt ?? $this->id;
        $target = DB::table('Target_KK')->where('Periode_Sensus', '2020')->where('ID_RT',$id)->first();
        return $target ? $target->Target_KK :  0;
    }
    
    public function getByID($id_rt=null)
    {
        $rows = $this->select('id_rt as id', 'nama_rt as text', 'RTID as kode');
        
        if (!empty($id_rt)) {
            if (is_array($id_rt)) {
                $rows = $rows->whereIn('id_rt', $id_rt);
            } else {
                $rows = $rows->where('id_rt', $id_rt);
            }
        }
        $rows = $rows->orderBy('nama_rt')->get();
        return $rows;
    }
    
    public function getByParent($id_rw=null)
    {
        // $rows = $this->select('id_rt as id', 'nama_rt as text', 'RTID as kode','Target_KK')->leftJoin('Target_KK','ID_RT','=','id_rt');
        $rows = $this->select('id_rt as id', 'nama_rt as text', 'RTID as kode');
        if (!empty($id_rw)) {
            if (is_array($id_rw)) {
                $rows = $rows->whereIn('id_rw', $id_rw);
            } else {
                $rows = $rows->where('id_rw', $id_rw);
            }
        }
        $rows = $rows->orderBy('nama_rt')->get();
        return $rows;
    }

    public function getByParents($id_rw=null)
    {
        $rows = $this->select('id_rt as id', 'nama_rt as text', 'RTID as kode');
        
        if (!empty($id_rw)) {
            if (is_array($id_rw)) {
                $rows = $rows->whereIn('id_rw', $id_rw);
            } else {
                $rows = $rows->where('id_rw', $id_rw);
            }
        }
        $rows = $rows->orderBy('nama_rt')->get();
        return $rows;
    }


    public function getByParentAll($id_rw=null)
    {
        $rows = $this->select('id_rt as id');
        
        if (!empty($id_rw)) {
            if (is_array($id_rw)) {
                $rows = $rows->whereIn('id_rw', $id_rw);
            } else {
                $rows = $rows->where('id_rw', $id_rw);
            }
        }
        $rows = $rows->orderBy('nama_rt')->get();
        
        foreach($rows as $row)
         {
            $arr[]= $row['id'];
         }

        return $arr;
    }
}
