<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Formulir extends Model
{
    public function refKB1()
    {
        $rows = DB::table('ref_kb')->selectRaw("LPAD(id_kb::text, 2, '0') as id, id_kb, question_text")->orderBy('id_kb')->get();
        
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_kb] = (array)$item;
        }
        return $ref;
    }
    
    public function refKB1Answer()
    {
        $rows = DB::table('ref_kb_answer')->selectRaw("LPAD(id_answer::text, 2, '0') as id, id_kb, id_answer, answer_text")->orderBy('id_kb')->orderBy('id_answer')->get();
        
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_kb][$item->id_answer] = (array)$item;
        }
        return $ref;
    }
    
    public function frmKB1($id_frm)
    {
        $rows = DB::table('frm_kb')->where('id_frm', $id_frm)->orderBy('id_kb')->get();
        
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_kb] = (array)$item;
        }
        return $ref;
    }
    
    public function frmKB1Answer($id_frm)
    {
        $rows = DB::table('frm_kb_answer')->where('id_frm', $id_frm)->orderBy('id_kb')->orderBy('id_answer')->get();
        
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_kb][$item->id_answer] = (array)$item;
        }
        return $ref;
    }
    
    public function refPK()
    {
        $rows = DB::table('ref_pk')->selectRaw("LPAD(id_pk::text, 2, '0') as id, id_pk, question_text")->orderBy('id_pk')->get();
        return $rows;
    }
    
    public function refPKAll()
    {
        $rows = $this->refPK();
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk] = (array)$item;
        }
        return $ref;
    }
    
    public function refPK01()
    {
        $rows = $this->refPK()->where('id_pk','<=',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk] = (array)$item;
        }
        return $ref;
    }
    
    public function refPK02()
    {
        $rows = $this->refPK()->where('id_pk','>',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk] = (array)$item;
        }
        return $ref;
    }
    
    public function refPKAnswer()
    {
        $rows = DB::table('ref_pk_answer')->selectRaw("LPAD(id_answer::text, 2, '0') as id, id_pk, id_answer, answer_text")->orderBy('id_pk')->orderBy('id_answer')->get();
        return $rows;
    }
    
    public function refPKAllAnswer()
    {
        $rows = $this->refPKAnswer();
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk][$item->id_answer] = (array)$item;
        }
        return $ref;
    }
    
    public function refPK01Answer()
    {
        $rows = $this->refPKAnswer()->where('id_pk','<=',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk][$item->id_answer] = (array)$item;
        }
        return $ref;
    }
    
    public function refPK02Answer()
    {
        $rows = $this->refPKAnswer()->where('id_pk','>',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk][$item->id_answer] = (array)$item;
        }
        return $ref;
    }
    
    public function frmPK($id_frm)
    {
        $rows = DB::table('frm_pk')->where('id_frm', $id_frm)->orderBy('id_pk')->get();
        return $rows;
    }
    
    public function frmPK01($id_frm)
    {
        $rows = $this->frmPK($id_frm)->where('id_pk','<=',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk] = (array)$item;
            $ref[$item->id_pk]['pilihan_text'] = $item->pilihan=='1' ? 'YA' : ($item->pilihan=='2' ? 'TIDAK' : ($item->pilihan=='3' ? 'TIDAK BERLAKU' : '_'));
        }
        return $ref;
    }
    
    public function frmPK02($id_frm)
    {
        $rows = $this->frmPK($id_frm)->where('id_pk','>',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk] = (array)$item;
        }
        return $ref;
    }
    
    public function frmPKAnswer($id_frm)
    {
        $rows = DB::table('frm_pk_answer')->where('id_frm', $id_frm)->orderBy('id_pk')->orderBy('id_answer')->get();
        return $rows;
    }
    
    public function frmPK01Answer($id_frm)
    {
        $rows = $this->frmPKAnswer($id_frm)->where('id_pk','<=',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk][$item->id_answer] = (array)$item;
        }
        return $ref;
    }
    
    public function frmPK02Answer($id_frm)
    {
        $rows = $this->frmPKAnswer($id_frm)->where('id_pk','>',18);
        $ref = [];
        foreach ($rows as $item) {
            $ref[$item->id_pk][$item->id_answer] = (array)$item;
        }
        return $ref;
    }
    
    
}
