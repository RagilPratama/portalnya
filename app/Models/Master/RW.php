<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class RW extends Model
{
    protected $table = 'RW';
    protected $primaryKey = 'id_rw';
    
    public function getByID($id_rw=null)
    {
        $rows = $this->select('id_rw as id', 'nama_rw as text', 'KodeDepdagri as kode');
        
        if (!empty($id_rw)) {
            if (is_array($id_rw)) {
                $rows = $rows->whereIn('id_rw', $id_rw);
            } else {
                $rows = $rows->where('id_rw', $id_rw);
            }
        }
        $rows = $rows->orderBy('nama_rw')->get();
        return $rows;
    }
    
    public function getByParent($id_kelurahan=null)
    {
        $rows = $this->select('id_rw as id', 'nama_rw as text', 'KodeDepdagri as kode');
        
        if (!empty($id_kelurahan)) {
            if (is_array($id_kelurahan)) {
                $rows = $rows->whereIn('id_kelurahan', $id_kelurahan);
            } else {
                $rows = $rows->where('id_kelurahan', $id_kelurahan);
            }
        }
        $rows = $rows->orderBy('nama_rw')->get();
        return $rows;
    }

    public function getByParents($id_kelurahan=null)
    {
        $rows = $this->select('id_rw as id', 'nama_rw as text', 'KodeDepdagri as kode');        
        
         if (!empty($id_kelurahan)) {
             if (is_array($id_kelurahan)) {
                $rows = $rows->whereIn('id_kelurahan', $id_kelurahan);
            } else {
                $rows = $rows->where('id_kelurahan', $id_kelurahan);
            }
         }
         $rows = $rows->orderBy('nama_rw')->get();
        return $rows;
    }


    public function getByParentss($id_kelurahan=null)
    {
        $rows = $this->select('id_rw as id');        
        
         if (!empty($id_kelurahan)) {
             if (is_array($id_kelurahan)) {
                $rows = $rows->whereIn('id_kelurahan', $id_kelurahan);
            } else {
                $rows = $rows->where('id_kelurahan', $id_kelurahan);
            }
         }
         $rows = $rows->orderBy('nama_rw')->get();

         foreach($rows as $row)
         {
            $arr[]= $row['id'];
         }

        return $arr;
    }


        public function getRwByParent($id_kelurahan, $id_RW)
    {
        $rows = $this->select('id_rw as id', 'nama_rw as text', 'KodeDepdagri as kode');
        
        if (!empty($id_kelurahan)) {
            if (is_array($id_kelurahan)) {
                $rows = $rows->whereIn('id_kelurahan', $id_kelurahan)->whereIn('id_rw', $id_RW);
            } else {
                $rows = $rows->where('id_kelurahan', $id_kelurahan)->where('id_rw', $id_RW);
            }
        }


        $rows = $rows->orderBy('nama_rw')->get();
        return $rows;
    }
}
