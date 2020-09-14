<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'Kecamatan';
    protected $primaryKey = 'id_kecamatan';
    
    public function getByID($id_kecamatan=null)
    {
        $rows = $this->select('id_kecamatan as id', 'nama_kecamatan as text', 'KodeDepdagri as kode');
        
        if (!empty($id_kecamatan)) {
            if (is_array($id_kecamatan)) {
                $rows = $rows->whereIn('id_kecamatan', $id_kecamatan);
            } else {
                $rows = $rows->where('id_kecamatan', $id_kecamatan);
            }
        }
        $rows = $rows->orderBy('nama_kecamatan')->get();
        return $rows;
    }
    
    public function getByParent($id_kabupaten=null)
    {
        $rows = $this->select('id_kecamatan as id', 'nama_kecamatan as text', 'KodeDepdagri as kode');
        
        if (!empty($id_kabupaten)) {
            if (is_array($id_kabupaten)) {
                $rows = $rows->whereIn('id_kabupaten', $id_kabupaten);
            } else {
                $rows = $rows->where('id_kabupaten', $id_kabupaten);
            }
        }
        $rows = $rows->orderBy('nama_kecamatan')->get();
        return $rows;
    }
    
    public function getByKabupaten($id=null)
    {
        $rows = $this->getByParent($id);
        return $rows;
    }
    
    public function getByProvinsi($id=null)
    {
        $rows = collect();
        $kabmodel = new Kabupaten();
        $kabrows = $kabmodel->getByParent($id);
        foreach ($kabrows as $rowkab) {
            $rowkec = $this->getByParent($rowkab->id);
            $rows = $rows->merge($rowkec);
        }
        return $rows;
    }
    
    private function baseview() {
        $sql = 'SELECT c.*, b.nama_kabupaten, p.id_provinsi, p.nama_provinsi
        FROM "Kecamatan" c
        INNER JOIN "Kabupaten" b ON b."id_kabupaten" = c."id_kabupaten"
        INNER JOIN "Provinsi" p ON p."id_provinsi" = b."id_provinsi"';
        $rows = \DB::select($sql);
        return collect($rows);
    }
}
