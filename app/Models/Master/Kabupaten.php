<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'Kabupaten';
    protected $primaryKey = 'id_kabupaten';
    
    public function getByID($id_kabupaten=null)
    {
        $rows = $this->select('id_kabupaten as id', 'nama_kabupaten as text', 'KodeDepdagri as kode');
        
        if (!empty($id_kabupaten)) {
            if (is_array($id_kabupaten)) {
                $rows = $rows->whereIn('id_kabupaten', $id_kabupaten);
            } else {
                $rows = $rows->where('id_kabupaten', $id_kabupaten);
            }
        }
        $rows = $rows->orderBy('nama_kabupaten')->get();
        return $rows;
    }
    
    public function getByParent($id_provinsi=null)
    {
        $rows = $this->select('id_kabupaten as id', 'nama_kabupaten as text', 'KodeDepdagri as kode');
        
        if (!empty($id_provinsi)) {
            if (is_array($id_provinsi)) {
                $rows = $rows->whereIn('id_provinsi', $id_provinsi);
            } else {
                $rows = $rows->where('id_provinsi', $id_provinsi);
            }
        }
        $rows = $rows->orderBy('nama_kabupaten')->get();
        return $rows;
    }
    
    public function getByProvinsi($id=null)
    {
        $rows = $this->getByParent($id);
        return $rows;
    }
    
    public function getByRegional($id=null)
    {
        $rows = collect();
        $model1 = new Provinsi();
        $rows1 = $model1->getByParent($id);
        foreach ($rows1 as $row) {
            $rows2 = $this->getByParent($row->id);
            $rows = $rows->merge($rows2);
        }
        return $rows;
    }
}
