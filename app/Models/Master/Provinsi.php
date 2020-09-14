<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'Provinsi';
    protected $primaryKey = 'id_provinsi';
    
    public function getByID($id_provinsi=null)
    {
        $rows = $this->select('id_provinsi as id', 'nama_provinsi as text', 'KodeDepdagri as kode');
        
        if (!empty($id_provinsi)) {
            if (is_array($id_provinsi)) {
                $rows = $rows->whereIn('id_provinsi', $id_provinsi);
            } else {
                $rows = $rows->where('id_provinsi', $id_provinsi);
            }
        }
        $rows = $rows->orderBy('nama_provinsi')->get();
        return $rows;
    }
    
    public function getByParent($id_regional=null)
    {
        $rows = $this->select('id_provinsi as id', 'nama_provinsi as text', 'KodeDepdagri as kode');
        
        if (!empty($id_regional)) {
            if (is_array($id_regional)) {
                $rows = $rows->whereIn('RegionalID', $id_regional);
            } else {
                if ($id_regional!=='*') {
                    $rows = $rows->where('RegionalID', $id_regional);
                }
            }
        }
        $rows = $rows->orderBy('nama_provinsi')->get();
        return $rows;
    }
}
