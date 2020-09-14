<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\DataTable;
use DB;

class IndikatorController extends Controller
{
    public $jsonResponse;

    public function __construct()
    {
        $this->jsonResponse = new \App\Models\Response();
    }

    public function index() 
    {
        $currentRoleID = currentUser('RoleID');
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->get();
        $indSarpras = DB::table("Parameter")->where('Group', 'IndSarpras')->orderBy('Code', 'asc')->get();
        $indPelatihan = DB::table("Parameter")->where('Group', 'IndPelatihan')->orderBy('Code', 'asc')->get();
        $indKelengkapan = DB::table("Parameter")->where('Group', 'IndKelengkapan')->orderBy('Code', 'asc')->get();
        if ($currentRoleID==2) {
            $indSarpras = $indSarpras->where('Code','=',1);
            $indPelatihan = $indPelatihan->where('Code','=',1);
            $indKelengkapan = $indKelengkapan->where('Code','=',1);
        } elseif($currentRoleID==6) {
            $indSarpras = $indSarpras->where('Code','<>',1);
            $indPelatihan = $indPelatihan->where('Code','<>',1);
            $indKelengkapan = $indKelengkapan->where('Code','<>',1);
        } else {
            $indSarpras = $indSarpras->where('Code','=','XXX');
            $indPelatihan = $indPelatihan->where('Code','=','XXX');
            $indKelengkapan = $indKelengkapan->where('Code','=','XXX');
        }
        return view('indikator.index')->with(compact('periode','indSarpras','indPelatihan', 'indKelengkapan'));
    }

    public function data(Request $request)
    {
        $currentRoleID = currentUser('RoleID');
        // $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $aksesWilayah  = currentUser('AksesWilayah')[0];
        $rows = DB::table('Indikator_Proses')
            ->where('periode_sensus','=', $request->input('PeriodeSensus'))
            ->where('id_provinsi','=',$aksesWilayah->id_provinsi);

        if ($currentRoleID==2)
        {
            $rows = $rows
            ->where('id_kabupaten','=',null)
            ->where('id_kecamatan','=',null);
        }
        elseif ($currentRoleID==6)
        {
            $rows = $rows
            ->where('id_kabupaten','=',$aksesWilayah->id_kabupaten)
            ->where('id_kecamatan','=',$aksesWilayah->id_kecamatan);
        }
        $data = $rows->get();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        try {
            $currentRoleID = currentUser('RoleID');
            // $aksesWilayah  = auth()->user()->AksesWilayah->first(q);
            $aksesWilayah  = currentUser('AksesWilayah')[0];
            $rows = DB::table('Indikator_Proses')
                ->where('periode_sensus','=', $request->input('PeriodeSensus'))
                ->where('id_provinsi','=',$aksesWilayah->id_provinsi);

            if ($currentRoleID==2)
            {
                $rows = $rows
                ->where('id_kabupaten','=',null)
                ->where('id_kecamatan','=',null);
            }
            elseif ($currentRoleID==6)
            {
                $rows = $rows
                ->where('id_kabupaten','=',$aksesWilayah->id_kabupaten)
                ->where('id_kecamatan','=',$aksesWilayah->id_kecamatan);
            }
            $delete = $rows->delete();

            if (!empty($request->IndSarpras)) {
                $IndSarpras = [];
                foreach ($request->IndSarpras as $idx=>$item) {
                    $data = [];
                    $data['periode_sensus'] = $request->PeriodeSensus;
                    $data['id_provinsi'] = $aksesWilayah->id_provinsi;
                    if (auth()->user()->RoleID==6) {
                        $data['id_kabupaten'] = $aksesWilayah->id_kabupaten;
                        $data['id_kecamatan'] = $aksesWilayah->id_kecamatan;
                    }
                    $data['ind_type'] = 'IndSarpras';
                    $data['ind_code'] = $idx;
                    $data['pengadaan'] = empty($item['pengadaan']) ? false : true;
                    $data['distribusi'] = empty($item['distribusi']) ? false : true;
                    $IndSarpras[$idx] = $data;
                }
                DB::table('Indikator_Proses')->insert($IndSarpras);
            }

                if (!empty($request->IndPelatihan)) {
                    $IndPelatihan = [];
                    foreach ($request->IndPelatihan as $idx=>$item) {
                        $data = [];
                        $data['periode_sensus'] = $request->PeriodeSensus;
                        $data['id_provinsi'] = $aksesWilayah->id_provinsi;
                        if (auth()->user()->RoleID==6) {
                            $data['id_kabupaten'] = $aksesWilayah->id_kabupaten;
                            $data['id_kecamatan'] = $aksesWilayah->id_kecamatan;
                        }
                        $data['ind_type'] = 'IndPelatihan';
                        $data['ind_code'] = $idx;
                        if (!empty($item['status_proses'])) {
                            $data['status_proses'] = empty($item['status_proses']) ? false : true;
                            $data['jml_peserta'] = empty($item['status_proses']) ? null : $item['jml_peserta'];
                            $IndPelatihan[$idx] = $data;
                        }
                    }
                    DB::table('Indikator_Proses')->insert($IndPelatihan);
                }
            
            if (!empty($request->IndKelengkapan)) {
                $IndKelengkapan = [];
                foreach ($request->IndKelengkapan as $idx=>$item) {
                    $data = [];
                    $data['periode_sensus'] = $request->PeriodeSensus;
                    $data['id_provinsi'] = $aksesWilayah->id_provinsi;
                    if (auth()->user()->RoleID==6) {
                        $data['id_kabupaten'] = $aksesWilayah->id_kabupaten;
                        $data['id_kecamatan'] = $aksesWilayah->id_kecamatan;
                    }
                    $data['ind_type'] = 'IndKelengkapan';
                    $data['ind_code'] = $idx;
                    if (!empty($item['status_proses'])) {
                        $data['status_proses'] = empty($item['status_proses']) ? false : true;
                        $IndKelengkapan[$idx] = $data;
                    }
                }
                DB::table('Indikator_Proses')->insert($IndKelengkapan);
            }

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return response()->json($this->jsonResponse->get());
    }

    public function monitoring()
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->get();
        return view('indikator.monitoring')->with(compact('periode'));
    }

    public function mondata()
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = currentUser('AksesWilayah')[0];
        // $aksesWilayah  = auth()->user()->AksesWilayah->first();

                $sql = '
                SELECT * FROM (
                SELECT 
                k.nama_provinsi
                , k.nama_kabupaten
                , k.nama_kecamatan
                , i.*
                , 3 as tingkat
                FROM v_kecamatan k
                LEFT JOIN v_indsarpras i ON i.id_kecamatan=k.id_kecamatan AND i.periode_sensus='.$periode.' 
                WHERE 
                 k.id_provinsi='.$aksesWilayah->id_provinsi.' 
                             
               UNION ALL
                        
               SELECT 
               nama_provinsi
              , nama_kabupaten
              , \'\' as nama_kecamatan
              , id_provinsi
              , id_kabupaten
              , null as id_kecamatan
              , '.$periode.'   as periode_sensus
              ,  AVG(COALESCE(pengadaan_1,0)) as pengadaan_1
        ,  AVG(COALESCE(distribusi_1,0)) as distribusi_1
        ,  AVG(COALESCE(pengadaan_2,0)) as pengadaan_2
        ,  AVG(COALESCE(distribusi_2,0)) as distribusi_2
        ,  AVG(COALESCE(pengadaan_3,0)) as pengadaan_3
        ,  AVG(COALESCE(distribusi_3,0)) as distribusi_3
        ,  AVG(COALESCE(pengadaan_4,0)) as pengadaan_4
        ,  AVG(COALESCE(distribusi_4,0)) as distribusi_4
        ,  AVG(COALESCE(pengadaan_5,0)) as pengadaan_5
        ,  AVG(COALESCE(distribusi_5,0)) as distribusi_5
        ,  AVG(COALESCE(pengadaan_6,0)) as pengadaan_6
        ,  AVG(COALESCE(distribusi_6,0)) as distribusi_6
        ,  AVG(COALESCE(pengadaan_7,0)) as pengadaan_7
        ,  AVG(COALESCE(distribusi_7,0)) as distribusi_7
        ,  AVG(COALESCE(pengadaan_8,0)) as pengadaan_8
        ,  AVG(COALESCE(distribusi_8,0)) as distribusi_8
        
              , 2 as tingkat
              FROM  (
              
              SELECT 
                      k.nama_provinsi
                      , k.nama_kabupaten
                      , k.nama_kecamatan
                      , k.id_provinsi
                              , k.id_kabupaten
                              , k.id_kecamatan
                              ,i.periode_sensus
        , i.pengadaan_1
        , i.distribusi_1
        , i.pengadaan_2
        , i.distribusi_2
        , i.pengadaan_3
        , i.distribusi_3
        , i.pengadaan_4
        , i.distribusi_4
        , i.pengadaan_5
        , i.distribusi_5
        , i.pengadaan_6
        , i.distribusi_6
        , i.pengadaan_7
        , i.distribusi_7
        , i.pengadaan_8
        , i.distribusi_8
                      FROM v_kecamatan k
                      LEFT JOIN v_indsarpras i ON i.id_kecamatan=k.id_kecamatan AND i.periode_sensus='.$periode.'  
                      WHERE 
                       k.id_provinsi='.$aksesWilayah->id_provinsi.'   
                   ) x 
                   GROUP BY nama_provinsi
              , nama_kabupaten
              , id_provinsi
              , id_kabupaten 
                             
                UNION ALL
                        
                        SELECT 
                k.nama_provinsi
                , \'\' nama_kabupaten
                , \'\' nama_kecamatan
                , i.*
                , 1 as tingkat
                FROM v_provinsi k
                LEFT JOIN v_indsarpras i ON i.id_provinsi=k.id_provinsi AND i.id_kabupaten IS null AND i.id_kecamatan IS null AND i.periode_sensus='.$periode.' 
                WHERE 
                 k.id_provinsi='.$aksesWilayah->id_provinsi.'  
                 ) x 
                 ORDER BY nama_provinsi, nama_kabupaten, nama_kecamatan
                ';
        $data = new DataTable($sql, ['searchFields'=>['nama_kabupaten', 'nama_kecamatan']]);
        $result = $data->get();
        return $this->jsonOutput($result);
    }

    public function mondatalatih()
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = currentUser('AksesWilayah')[0];
        // $aksesWilayah  = auth()->user()->AksesWilayah->first();

        $sql = '
        SELECT * FROM (
        SELECT 
        k.nama_provinsi
        , k.nama_kabupaten
        , k.nama_kecamatan
        , i.*
        , 3 as tingkat
        FROM v_kecamatan k
        LEFT JOIN v_indpelatihan i ON i.id_kecamatan=k.id_kecamatan AND i.periode_sensus='.$periode.' 
        WHERE 
         k.id_provinsi='.$aksesWilayah->id_provinsi.' 
                     
       UNION ALL
       
       SELECT 
       nama_provinsi
      , nama_kabupaten
      , \'\' as nama_kecamatan
      , id_provinsi
      , id_kabupaten
      , null as id_kecamatan
      , '.$periode.'  as periode_sensus
      , \'IndPelatihan\' ind_type
      , SUM(status_proses_1) as status_proses_1
      , SUM(jml_peserta_1) as jml_peserta_1
      , SUM(status_proses_2) as status_proses_2
      , SUM(jml_peserta_2) as jml_peserta_2
      , SUM(status_proses_3) as status_proses_3
      , SUM(jml_peserta_3) as jml_peserta_3
      , 2 as tingkat
      FROM  (
      
      SELECT 
              k.nama_provinsi
              , k.nama_kabupaten
              , k.nama_kecamatan
              , k.id_provinsi
                      , k.id_kabupaten
                      , k.id_kecamatan
                      , i.jml_peserta_1
                      , i.status_proses_1
                      , i.jml_peserta_2
                      , i.status_proses_2
                      , i.jml_peserta_3
                      , i.status_proses_3
                      , i.periode_sensus
                      , i.ind_type
              FROM v_kecamatan k
              LEFT JOIN v_indpelatihan i ON i.id_kecamatan=k.id_kecamatan AND i.periode_sensus='.$periode.'  
              WHERE 
               k.id_provinsi='.$aksesWilayah->id_provinsi.'  
           ) x 
           GROUP BY nama_provinsi
      , nama_kabupaten
      , id_provinsi
      , id_kabupaten
                     
        UNION ALL
                
                SELECT 
        k.nama_provinsi
        , \'\' nama_kabupaten
        , \'\' nama_kecamatan
        , i.*
        , 1 as tingkat
        FROM v_provinsi k
        LEFT JOIN v_indpelatihan i ON i.id_provinsi=k.id_provinsi AND i.id_kabupaten IS null AND i.id_kecamatan IS null AND i.periode_sensus='.$periode.' 
        WHERE 
         k.id_provinsi='.$aksesWilayah->id_provinsi.'  
         ) x 
         ORDER BY nama_provinsi, nama_kabupaten, nama_kecamatan, tingkat
        ';
        // debug($sql,1,1);
        $data = new DataTable($sql, ['searchFields'=>['nama_kabupaten', 'nama_kecamatan']]);
        $result = $data->get();
        return $this->jsonOutput($result);
    }

    public function mondatalengkap()
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = currentUser('AksesWilayah')[0];
        // $aksesWilayah  = auth()->user()->AksesWilayah->first();

        $sql = '
        SELECT * FROM (
            SELECT 
            k.nama_provinsi
            , k.nama_kabupaten
            , k.nama_kecamatan
            , i.*
            , 3 as tingkat
            FROM v_kecamatan k
            LEFT JOIN v_indkelengkapan i ON i.id_kecamatan=k.id_kecamatan AND i.periode_sensus='.$periode.' 
            WHERE 
             k.id_provinsi='.$aksesWilayah->id_provinsi.' 
                         
           UNION ALL
           SELECT 
           nama_provinsi
          , nama_kabupaten
          , \'\' as nama_kecamatan
          , id_provinsi
          , id_kabupaten
          , null as id_kecamatan
          , '.$periode.'  as periode_sensus
          , \'IndKelengkapan\' ind_type
          , SUM(organisasi) as organisasi
          , SUM(posko) as posko
          , 2 as tingkat
          FROM  (
          
          SELECT 
                  k.nama_provinsi
                  , k.nama_kabupaten
                  , k.nama_kecamatan
                  , k.id_provinsi
                          , k.id_kabupaten
                          , k.id_kecamatan
                          , i.organisasi
                          , i.posko
                          , i.periode_sensus
                          , i.ind_type
                  FROM v_kecamatan k
                  LEFT JOIN v_indkelengkapan i ON i.id_kecamatan=k.id_kecamatan AND i.periode_sensus='.$periode.'  
                  WHERE 
                   k.id_provinsi='.$aksesWilayah->id_provinsi.'  
               ) x 
               GROUP BY nama_provinsi
          , nama_kabupaten
          , id_provinsi
          , id_kabupaten

            UNION ALL
                    
                    SELECT 
            k.nama_provinsi
            , \'\' nama_kabupaten
            , \'\' nama_kecamatan
            , i.*
            , 1 as tingkat
            FROM v_provinsi k
            LEFT JOIN v_indkelengkapan i ON i.id_provinsi=k.id_provinsi AND i.id_kabupaten IS null AND i.id_kecamatan IS null AND i.periode_sensus='.$periode.' 
            WHERE 
             k.id_provinsi='.$aksesWilayah->id_provinsi.'  
             ) x 
             ORDER BY nama_provinsi, nama_kabupaten, nama_kecamatan, tingkat
        ';
        // debug($sql,1,1);
        $data = new DataTable($sql, ['searchFields'=>['nama_kabupaten', 'nama_kecamatan']]);
        $result = $data->get();
        return $this->jsonOutput($result);
    }
}
