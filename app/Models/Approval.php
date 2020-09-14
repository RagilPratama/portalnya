<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Approval extends Model
{
    public function isApproved($id_kecamatan, $obj='wilayah') 
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $lock_prov = DB::table('Mon_Wilayah_Prov AS a')
            ->join('v_kecamatan AS v', 'a.ID_Provinsi', 'v.id_provinsi')
            ->where('a.Periode_Sensus', $periode)
            ->where('v.id_kecamatan', $id_kecamatan)
            ->first();

        if ($lock_prov && !$lock_prov->Status_Open)
            return true;
        
        if ($lock_prov && $lock_prov->Status_Open) {
            $startDate = Carbon::createFromFormat('Y-m-d', $lock_prov->Start_Date_Open ?? Carbon::now()->subDays(1)->format('Y-m-d'));
            $endDate = Carbon::createFromFormat('Y-m-d', $lock_prov->End_Date_Open ?? Carbon::now()->subDays(1)->format('Y-m-d'));
            $check = Carbon::now()->between($startDate,$endDate);
            if ($lock_prov && $lock_prov->Status_Open && !$check)
                return true;
        }
            
        $lock_kec = DB::table('Mon_Wilayah_Kec')
            ->where('Periode_Sensus', $periode)
            ->where('ID_Kecamatan', $id_kecamatan)
            ->first();

            if ($obj=='wilayah') {
                if ($lock_kec && $lock_kec->Status_Approve_Kec)
                    return true;
            } else {
                if ($lock_kec && $lock_kec->Status_Approve_Target)
                    return true;
            }
        
        return false;
    }
}
