<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Formulir;
use App\Libraries\DataTable;

class FormulirController extends Controller
{
    public function demografi($idfrm)
    {
        $sql = 'SELECT *, TO_CHAR(b.tgl_lahir, \'DD/MM/YYYY\') as tgl_lahir_id
            FROM mst_formulir a
            INNER JOIN mst_formulir_dtl b ON b.id_frm=a.id_frm
            WHERE a.id_frm=\''.$idfrm.'\'
            ORDER BY no_urutnik';
        // $rows = \DB::select($sql, [$idfrm]);
        // debug($sql);exit;
        $data = new DataTable($sql, ['searchFields'=>['no_urutnik', 'nama_anggotakel']]);
        return $this->jsonOutput($data->get());
    }
    
    public function kb1form($id_frm='')
    {
        
        $model = new Formulir();
        
        $refkb = $model->refKB1();
        $refanswer = $model->refKB1Answer();
        $frmkb = $model->frmKB1($id_frm);
        $frmkbanswer = $model->frmKB1Answer($id_frm);
        
        return view('laporan.form_kb1')->with(compact('refkb','refanswer', 'frmkb', 'frmkbanswer'));
        
        
    }
    
    public function pk01form($id_frm='')
    {
        $model = new Formulir();
        
        $refpk = $model->refPK01();
        $refanswer = $model->refPK01Answer();
        $frmpk = $model->frmPK01($id_frm);
        $frmpkanswer = $model->frmPK01Answer($id_frm);
        return view('laporan.form_pk01')->with(compact('refpk','refanswer', 'frmpk', 'frmpkanswer'));
        
    }
    
    public function pk02form($id_frm='')
    {
        $model = new Formulir();
        
        $refpk = $model->refPK02();
        $refanswer = $model->refPK02Answer();
        $frmpk = $model->frmPK02($id_frm);
        $frmpkanswer = $model->frmPK02Answer($id_frm);
        return view('laporan.form_pk02')->with(compact('refpk','refanswer', 'frmpk', 'frmpkanswer'));
        
    }
    
}
