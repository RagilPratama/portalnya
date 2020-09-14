<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\jqGrid;
use App\Models\Response;

class ApprovalController extends Controller
{   
    public function kecamatanIndex () {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->get();
        return view('approval.kecamatan')->with(compact('periode'));
    }

    public function kecamatanData () {
        $periode = request()->get('PeriodeSensus');
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        if (request()->get('Status')==1) {
            $sql = 'SELECT a."Status_Approve_Kec", a."Status_Approve_Target", 
                x.id_kecamatan, x.nama_kecamatan, y.id_kabupaten, y.nama_kabupaten, z.id_provinsi, z.nama_provinsi
                FROM "Kecamatan" x
                LEFT JOIN "Mon_Wilayah_Kec" a ON x.id_kecamatan=a."ID_Kecamatan" AND a."Periode_Sensus"='.$periode.' 
                INNER JOIN "Kabupaten" y ON x.id_kabupaten=y.id_kabupaten
                INNER JOIN "Provinsi" z ON y.id_provinsi=z.id_provinsi AND z.id_provinsi='.$aksesWilayah->id_provinsi.'
                WHERE (a."Status_Approve_Kec" IS TRUE AND a."Status_Approve_Target" IS TRUE)';

        } else {
            $sql = 'SELECT a."Status_Approve_Kec", a."Status_Approve_Target", 
                x.id_kecamatan, x.nama_kecamatan, y.id_kabupaten, y.nama_kabupaten, z.id_provinsi, z.nama_provinsi
                FROM "Kecamatan" x
                LEFT JOIN "Mon_Wilayah_Kec" a ON x.id_kecamatan=a."ID_Kecamatan" AND a."Periode_Sensus"='.$periode.' 
                INNER JOIN "Kabupaten" y ON x.id_kabupaten=y.id_kabupaten
                INNER JOIN "Provinsi" z ON y.id_provinsi=z.id_provinsi AND z.id_provinsi='.$aksesWilayah->id_provinsi.'
                WHERE NOT (a."Status_Approve_Kec" IS TRUE AND a."Status_Approve_Target" IS TRUE)';

        }
        $rows = new jqGrid($sql);
        $data = $rows->get();
        return response()->json($data);
    }

    public function kecamatanClose () {
        $response = new Response();
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $statement = DB::table('Mon_Wilayah_Kec')->where('Periode_Sensus', $periode)
            ->where('ID_Provinsi', $aksesWilayah->id_provinsi)
            ->where('ID_Kabupaten', $aksesWilayah->id_kabupaten)
            ->where('ID_Kecamatan', $aksesWilayah->id_kecamatan);
        $row = $statement->first();
        if ($row) {
            $statement->update([
                'Status_Approve_Kec' => 1
            ]);
        } else {
            DB::table('Mon_Wilayah_Kec')->insert([
                'Periode_Sensus' => $periode,
                'ID_Provinsi' => $aksesWilayah->id_provinsi,
                'ID_Kabupaten' => $aksesWilayah->id_kabupaten,
                'ID_Kecamatan' => $aksesWilayah->id_kecamatan,
                'Status_Approve_Kec' => 1
            ]);
        }
        $response->status = true;
        $response->message = 'Data berhasil disetujui';
        return response()->json($response->get());
    }

    public function kecamatanOpen () {
        $response = new Response();
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $statement = DB::table('Mon_Wilayah_Kec')->where('Periode_Sensus', $periode)
            ->where('ID_Provinsi', $aksesWilayah->id_provinsi)
            ->where('ID_Kabupaten', $aksesWilayah->id_kabupaten)
            ->where('ID_Kecamatan', $aksesWilayah->id_kecamatan);
        $row = $statement->first();
        if ($row) {
            if ($row->Status_Approve_Target) {
                $statement->update([
                    'Status_Approve_Kec' => 0
                ]);
            } else {
                $statement->delete();
            }
        }
        $response->status = true;
        $response->message = 'Data berhasil dibuka';
        return response()->json($response->get());
    }

    public function kecamatanCloseTarget () {
        $response = new Response();
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $statement = DB::table('Mon_Wilayah_Kec')->where('Periode_Sensus', $periode)
            ->where('ID_Provinsi', $aksesWilayah->id_provinsi)
            ->where('ID_Kabupaten', $aksesWilayah->id_kabupaten)
            ->where('ID_Kecamatan', $aksesWilayah->id_kecamatan);
        $row = $statement->first();
        if ($row) {
            $statement->update([
                'Status_Approve_Target' => 1
            ]);
        } else {
            DB::table('Mon_Wilayah_Kec')->insert([
                'Periode_Sensus' => $periode,
                'ID_Provinsi' => $aksesWilayah->id_provinsi,
                'ID_Kabupaten' => $aksesWilayah->id_kabupaten,
                'ID_Kecamatan' => $aksesWilayah->id_kecamatan,
                'Status_Approve_Target' => 1
            ]);
        }
        $response->status = true;
        $response->message = 'Data berhasil disetujui';
        return response()->json($response->get());
    }

    public function kecamatanOpenTarget () {
        $response = new Response();
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $statement = DB::table('Mon_Wilayah_Kec')->where('Periode_Sensus', $periode)
            ->where('ID_Provinsi', $aksesWilayah->id_provinsi)
            ->where('ID_Kabupaten', $aksesWilayah->id_kabupaten)
            ->where('ID_Kecamatan', $aksesWilayah->id_kecamatan);
        $row = $statement->first();
        if ($row) {
            if ($row->Status_Approve_Kec) {
                $statement->update([
                    'Status_Approve_Target' => 0
                ]);
            } else {
                $statement->delete();
            }
        }
        $response->status = true;
        $response->message = 'Data berhasil dibuka';
        return response()->json($response->get());
    }

    public function provinsiIndex () {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->get();
        return view('approval.provinsi')->with(compact('periode'));

    }

    public function provinsiData () {
        $periode = request()->get('PeriodeSensus');
        $aksesWilayah  = auth()->user()->AksesWilayah->first();

        $count = DB::table('Mon_Wilayah_Prov')->get()->count();


        if ($count > 0 ) {
            if (request()->get('Status')==1) {
                $sql = 'SELECT \'1\' status, x.nama_provinsi, x."id_provinsi", a.*  FROM "Provinsi" x 
                    LEFT JOIN "Mon_Wilayah_Prov" a ON x.id_provinsi=a."ID_Provinsi" AND a."Periode_Sensus"='.$periode.' and a."Status_Open"                 
                    LEFT JOIN "Provinsi" z ON a."ID_Provinsi"=z.id_provinsi  AND z.id_provinsi='.$aksesWilayah->id_provinsi.'                 
                    order by "Status_Open", x.nama_provinsi, x."id_provinsi"';

            } else {
                $sql = 'SELECT \'1\' status, x.nama_provinsi, x."id_provinsi", a.*  FROM "Provinsi" x 
                    LEFT JOIN "Mon_Wilayah_Prov" a ON x.id_provinsi=a."ID_Provinsi" AND a."Periode_Sensus"='.$periode.' and not a."Status_Open"                 
                    LEFT JOIN "Provinsi" z ON a."ID_Provinsi"=z.id_provinsi  AND z.id_provinsi='.$aksesWilayah->id_provinsi.'                 
                    order by "Status_Open", x.nama_provinsi, x."id_provinsi"';

            }
        } else {
                $sql = 'SELECT \'0\' status, x.nama_provinsi, x."id_provinsi", a."Periode_Sensus", true "Status_Open", "Start_Date_Open", "End_Date_Open"  
                        FROM "Provinsi" x 
                    LEFT JOIN "Mon_Wilayah_Prov" a ON x.id_provinsi=a."ID_Provinsi" AND a."Periode_Sensus"='.$periode.' and not a."Status_Open"                 
                    LEFT JOIN "Provinsi" z ON a."ID_Provinsi"=z.id_provinsi  AND z.id_provinsi='.$aksesWilayah->id_provinsi.'                 
                    order by "Status_Open", x.nama_provinsi, x."id_provinsi"';
        }

        //debug($sql); exit;

        $rows = new jqGrid($sql);
        $data = $rows->get();
        return response()->json($data);
    }    

    public function provinsiClose () {

        $periode = request()->input('PeriodeSensus');
        $opendate = request()->input('opendate');
        $closedate = request()->input('closedate');

        $idprovinsi = request()->input('idprovinsi');

        $response = new Response();
        $aksesWilayah  = auth()->user()->AksesWilayah->first();

        $statement = DB::table('Mon_Wilayah_Prov')->where('Periode_Sensus', $periode)
            ->where('ID_Provinsi', $idprovinsi);
        $row = $statement->first();

        if ($row) {
            if ($row->Status_Open) {
                
                try {
                    $statement->update([
                        'Start_Date_Open' => $opendate,
                        'End_Date_Open' => $closedate,
                        'Status_Open' => 0
                    ]);                   

                    $response->status = true;
                    $response->message = 'Data berhasil ditutup';

                } catch (\Exception $e) {
                   $response->status = false;
                   $response->message = getExceptionMessage($e);
                }
            } 
        }
        
        return response()->json($response->get());
    }
    
    public function provinsiOpen () {


        //$closedate = $request->closedate;
        $periode = request()->input('PeriodeSensus');
        $opendate = request()->input('opendate');
        $closedate = request()->input('closedate');

        $idprovinsi = request()->input('idprovinsi');

        //Carbon::parse($closedate);

        $response = new Response();
        //$periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = auth()->user()->AksesWilayah->first();

        $statement = DB::table('Mon_Wilayah_Prov')->where('Periode_Sensus', $periode)
            ->where('ID_Provinsi', $idprovinsi);
        $row = $statement->first();

        //var_dump($row->Status_Open); exit;
        
        if ($row) {
            if (!$row->Status_Open) {
                
                try {
                    $statement->update([
                        'Start_Date_Open' => $opendate,
                        'End_Date_Open' => $closedate,
                        'Status_Open' => 1
                    ]);                   

                    $response->status = true;
                    $response->message = 'Data berhasil dibuka';

                } catch (\Exception $e) {
                   $response->status = false;
                    $response->message = getExceptionMessage($e);
               //     //$this->jsonResponse->message = getExceptionMessage($e);
                }
            } //else {
             //   $statement->delete();
            //}
        }
        
        return response()->json($response->get());

    }
}
