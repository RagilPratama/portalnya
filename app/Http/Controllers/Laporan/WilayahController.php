<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Kelurahan;
use DB;
class WilayahController extends Controller
{
    public function kelurahan() {
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $sql = 'SELECT * FROM "Kelurahan" a
            LEFT JOIN (
                SELECT b."WilayahID", c."Smartphone", c."NoTelepon", c."UserName" FROM "UserAkses" b 
                INNER JOIN "User" c ON c."ID"=b."UserID" and c."RoleID"=4 and c."Smartphone"=true
            ) d ON a.id_kelurahan=d."WilayahID"
            WHERE a.id_kecamatan='.$aksesWilayah->id_kecamatan;
        $rows = DB::select($sql);
        return view('laporan.pic_kelurahan')->with(compact('aksesWilayah','rows'));
    }
}
