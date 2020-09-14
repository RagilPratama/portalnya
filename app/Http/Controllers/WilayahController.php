<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use App\Models\UserAkses;
use App\Models\User;
use App\Models\Approval;
use App\Libraries\jqGrid;
use DB;

class WilayahController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:wilmgmt')->only(['index']);
        $this->middleware('checkPermission:wilmgmtkec')->only(['mkecamatan']);
        $this->middleware('checkPermission:wiltarget')->only(['target']);
    }
    
    public function akses()
    {
        ini_set('max_execution_time', 0); 
        $model = new Wilayah();
        $rows = $model->getTreeWilayah3();
        return $this->jsonOutput($rows);
    }

    public function updateAkses()
    {
        ini_set('max_execution_time', 0); 
        $model = new UserAkses();
        $rows = $model->postUpdateAkses();
        return $this->jsonOutput($rows);
    }


    public function provinsi($id)
    {
        $wilmodel = new Wilayah();
        $rows = $wilmodel->getProvinces($id);
        return $this->jsonOutput($rows);
    }

    public function kotakab($id)
    {
        $wilmodel = new Wilayah();
        $rows = $wilmodel->getRegencies($id);
        return $this->jsonOutput($rows);
    }

    public function kecamatan($id)
    {
        $wilmodel = new Wilayah();
        $rows = $wilmodel->getDistricts($id);
        return $this->jsonOutput($rows);
    }

    public function kelurahan($id)
    {
        $wilmodel = new Wilayah();
        $rows = $wilmodel->getVillages($id);
        return $this->jsonOutput($rows);
    }

    public function kelurahans($id)
    {
        $wilmodel = new Wilayah();
        $rows = $wilmodel->getKelurahanBy($id);
        return $this->jsonOutput($rows);
    }

    public function rw($id)
    {
         $wilmodel = new \App\Models\Master\RW();
         
         $userID = currentUser('ID');
         $userAkses = UserAkses::where('UserID', $userID)->get()->pluck('WilayahID')->toArray();

         //var_dump($userAkses); exit;

        // if ($id == 'all') {
        //     $ids = '';
        // } else {
            $ids = $id;
        // }
        $rows = $wilmodel->getByParent($ids);
        return $this->jsonOutput($rows);
    }

    public function rwby($id, $ids)
    {
        // var_dump($id);
        // var_dump($ids);
        $wilmodel = new \App\Models\Master\RW();
        $rows = $wilmodel->getRwByParent($id, $ids);
        return $this->jsonOutput($rows);
    }

    public function rt($id)
    {
        $wilmodel = new \App\Models\Master\RT();
        $rows = $wilmodel->getByParent($id);
        return $this->jsonOutput($rows);
    }

    public function rts(Request $request)
    {
        $wilmodel = new \App\Models\Master\RT();
        $rows = $wilmodel->getByParents($request['data']);
        return $this->jsonOutput($rows);
    }

    public function rws(Request $request)
    {
        //$kelurahan = implode(',', (array)$id);
        //return $request;

        //debug($kl); exit;
        $wilmodel = new \App\Models\Master\RW();
        $rows = $wilmodel->getByParents($request['data']);
        return $this->jsonOutput($rows);
    }

    public function wilayahkoe()
    {
        //$wilmodel = new Wilayah();
        $rows = Kelurahan::all(); // $wilmodel->getWialayah();
        return $this->jsonOutput($rows);
    }

    public function index()
    {

        $userID = currentUser('ID');
        $userAkses = UserAkses::where('UserID', $userID)->get()->pluck('WilayahID')->toArray();
        $valwilayah = 1;
        $model = new Wilayah;
        $provinsi = $model->getDataProvinsi();       
        //$kabupaten = $model->getDataKabupaten(id);       

        //debug($wilayah); exit;
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;       
        $aksesWilayah  = auth()->user()->AksesWilayah->first(); 
        $row = DB::table('Mon_Wilayah_Prov')->where('Periode_Sensus', $periode)
            ->where('ID_Provinsi', $aksesWilayah->id_provinsi)
            //->where('ID_Kabupaten', $aksesWilayah->id_kabupaten)
            //->where('ID_Kecamatan', $aksesWilayah->id_kecamatan)
            ->first();
        $approved = $row ? ($row->Status_Approve_Kec ? true : false) : false; 


        return view('wilayah.wilayahindex')->with(compact('provinsi', 'valwilayah', 'periode', 'approved'));
    }
    
    public function mkecamatan()
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $approved = (new Approval())->isApproved($aksesWilayah->id_kecamatan, 'target');
        // $row = DB::table('Mon_Wilayah_Kec')->where('Periode_Sensus', $periode)
        //     ->where('ID_Provinsi', $aksesWilayah->id_provinsi)
        //     ->where('ID_Kabupaten', $aksesWilayah->id_kabupaten)
        //     ->where('ID_Kecamatan', $aksesWilayah->id_kecamatan)
        //     ->first();
        // $approved = $row ? ($row->Status_Approve_Kec ? true : false) : false; 
        return view('wilayah.mkecamatan')->with(compact('periode', 'approved'));
    }
    

    public function getDataProvinsi()
    {
        $cond = " 1=1 ";
        if (currentUser('TingkatWilayahID')>=1) {
            $wilsess = session()->get('user.AksesWilayah')[0];
            $idwil = $wilsess->id_provinsi;
            $cond = " id_provinsi={$idwil} ";
        }
        $sql = 'select * from "Provinsi" WHERE '.$cond.' order by id_provinsi';

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_provinsi', 'id_provinsi']]);
        $users = $data->get();

        return $this->jsonOutput($users);
    }


    public function getDataKabupaten($id)
    {
        $cond = " 1=1 ";
        if (currentUser('TingkatWilayahID')>=2) {
            $wilsess = session()->get('user.AksesWilayah')[0];
            $idwil = $wilsess->id_kabupaten;
            $cond = " id_kabupaten={$idwil} ";
        }
        
        $sql = 'select * from "Kabupaten" where id_provinsi = '.$id.' AND '.$cond.' order by id_kabupaten asc';

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_kabupaten', 'id_kabupaten']]);
        $kabupaten = $data->get();

        return $this->jsonOutput($kabupaten);
    }

    public function getDataKecamatan($id=0)
    {
        $wilsess = session()->get('user.AksesWilayah')[0];
        if ($id==0) {
            $id = $wilsess->id_kabupaten;
        }
        $cond = " 1=1 ";
        if (currentUser('TingkatWilayahID')>=3) {
            $idwil = $wilsess->id_kecamatan;
            $cond = " id_kecamatan={$idwil} ";
        }
        $sql = 'select * from "Kecamatan" where id_kabupaten = '.$id.' AND '.$cond.' and "IsActive" order by id_kecamatan asc ';

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_kecamatan', 'id_kecamatan']]);
        $kecamatan = $data->get();

        return $this->jsonOutput($kecamatan);
    }

    public function getDataKelurahan($id)
    {
        if ($id == 'all') {
            $sql = 'select * from "Kelurahan" where id_kecamatan = '.$aksesWilayah->id_kecamatan.' and "IsActive" order by id_kelurahan asc';
        } else {
            $sql = 'select * from "Kelurahan" where id_kecamatan = '.$id.' and "IsActive" order by id_kelurahan asc';    
        }
        

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_kelurahan', 'id_kelurahan']]);
        $kecamatan = $data->get();

        return $this->jsonOutput($kecamatan);
    }


    public function getDataKelurahans($id)
    {
        if ($id == 'all') {
            $sql = 'select * from "Kelurahan" where id_kecamatan = '.$aksesWilayah->id_kecamatan.' and "IsActive" order by id_kelurahan asc';
        } else {
            $sql = 'select * from "Kelurahan" where id_kelurahan = '.$id.' and "IsActive" order by id_kelurahan asc';    
        }
        

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_kelurahan', 'id_kelurahan']]);
        $kecamatan = $data->get();

        return $this->jsonOutput($kecamatan);
    }


    public function getDataRw($id)
    {
        $sql = 'select * from "RW" where id_kelurahan = '.$id.' and "IsActive" order by id_rw desc';

        //var_dump($sql);

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_rw', 'id_rw']]);
        $kecamatan = $data->get();

        return $this->jsonOutput($kecamatan);
    }

    public function getDataRwby($id, $ids)
    {
        $sql = 'select * from "RW" where id_kelurahan = '.$id.' and id_rw = '.$ids.' and "IsActive" order by id_rw desc';

        // var_dump($sql);

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_rw', 'id_rw']]);
        $kecamatan = $data->get();

        return $this->jsonOutput($kecamatan);
    }

    public function getDataRt($id)
    {
        $sql = 'select * from "RT" where id_rw = '.$id.' order by id_rt desc';

        $rows = \DB::select($sql);

        $collection = $rows; //$this->with(['role','wilayah'])->orderby('ID', 'desc');
        //$data = new DataTable($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        //$data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $data = new jqGrid($sql, ['searchFields'=>['nama_rt', 'id_rt']]);
        $kecamatan = $data->get();

        return $this->jsonOutput($kecamatan);
    }


    public function AddPostRW(Request $request)
    {
        $usermodel = new Wilayah();
        $result = $usermodel->postAddRW();
        return $this->jsonOutput($result);
    }


    public function cekDobelKodeRW(Request $request)
    {
       
        $request = request()->all();
        $count = DB::table('RW')->where('id_kelurahan',  $request['idkel'])->where('KodeDepdagri', $request['KodeDepdagri'])->count();

        if ($count > 0 ) { 
            $result = ['status' => false,'message' => 'Kode RW Sudah Ada Cek Ulang Inputan, Terima kasih.'];                   
        } else {
            $result = ['status' => true,'message' => 'Yeaaah... its Success.'];         
        }       
        
        return $result;
    }

    public function EditPostRW(Request $request)
    {
        $edit = new Wilayah();
        $result = $edit->postEditRW();
        return $this->jsonOutput($result);
    }

    public function AddPostRT(Request $request)
    {
        $usermodel = new Wilayah();
        $result = $usermodel->postAddRT();

        return $this->jsonOutput($result);
    }

    public function cekDobelKodeRT(Request $request)
    {
       
        $request = request()->all();
        $count = DB::table('RT')->where('id_rw', $request['idRW'])->where('KodeRT', $request['KodeRT'])->count();

        if ($count > 0 ) { 
            $result = ['status' => false,'message' => 'Kode RT Sudah Ada Cek Ulang Inputan, Terima kasih.'];                   
        } else {
            $result = ['status' => true,'message' => 'Yeaaah... its Success.'];         
        }       
        
        return $result;
    }


    public function EditPostRT(Request $request)
    {
        $edit = new Wilayah();
        $result = $edit->postEditRT();
        return $this->jsonOutput($result);
    }

    public function DeletePostRT(Request $request)
    {
        $edit = new Wilayah();
        $result = $edit->deleteEditRT();
        return $this->jsonOutput($result);
    }



    public function DeletePostRW(Request $request)
    {
        // check detail data RT
        $edit = new Wilayah();
        $result = $edit->deleteEditRW();
        return $this->jsonOutput($result);        
         

    }

    // Ubah wilayah Parent (Provinsi, Kabupaten, Kecamatan, Kelurahan)
    public function UbahWilayahParent ($id, $Key, $kondisi) {
        
        
        $sqlUpdate = '';

        $sqlrt = 'update "RT" a
                   set "KelurahanID"      = b.id_kelurahan
                        , "KodeKelurahan" = b."KodeDepdagriKelurahan"
                        , "KecamatanID"   = b.id_kecamatan
                        , "KodeKecamatan" = b."KodeDepdagriKecamatan"
                        , "KabupatenID"   = b.id_kabupaten
                        , "KodeKabupaten" = b."KodeDepdagriKabupaten"
                        , "ProvinsiID"    = b.id_provinsi
                        , "KodeProvinsi"  = b."KodeDepdagriProvinsi"
                    from v_data_wilayah_rw b        
                    where a.id_rw = b.id_rw '; 

        if ($kondisi == 1) {             
             $sqlUpdate = 'Update "Kabupaten" set id_provinsi = \''.$id.'\' where id_kabupaten = \''.str_replace(' ', '', $Key).'\'';
             $sqlrt =  $sqlrt. ' and a.id_kabupaten =  \''.str_replace(' ', '', $Key).'\'';                          
        }  else if ($kondisi == 2) {                          
             $sqlUpdate = 'Update "Kecamatan" set id_kabupaten = \''.$id.'\' where id_kecamatan = \''.str_replace(' ', '', $Key).'\'';
             $sqlrt =  $sqlrt. ' and a.id_kecamatan = \''.str_replace(' ', '', $Key).'\'';                       
        }  else if ($kondisi == 3) {                             
             $sqlUpdate = 'Update "Kelurahan" set id_kecamatan = \''.$id.'\' where id_kelurahan = \''.str_replace(' ', '', $Key).'\'';
             $sqlrt =  $sqlrt. ' and a.id_kelurahan = \''.str_replace(' ', '', $Key).'\'';    
        }  else if ($kondisi == 4) {
             $sqlUpdate = 'Update "RW" set id_kelurahan = \''.$id.'\' where id_rw = \''.str_replace(' ', '', $Key).'\'';
             $sqlrt =  $sqlrt. ' and a.id_rw = \''.str_replace(' ', '', $Key).'\'';            
        }  else if ($kondisi == 5) {
             $sqlUpdate = 'Update "RT" set id_rw = \''.$id.'\' where id_rt = \''.str_replace(' ', '', $Key).'\'';
             $sqlrt =  $sqlrt. ' and a.id_rt = \''.str_replace(' ', '', $Key).'\'';  
        }

        //debug($sqlUpdate); exit;
        try
        {
             DB::statement($sqlUpdate);

             // Update Data RT   
             DB::statement($sqlrt);  

             $result = ['status' => true,'message' => 'Yeaaah... its Success.']; 
        }
        catch(Exception $e)
        {
            $result = ['status' => false,'message' => $e->getMessage()];
        }
             

       

       return $result;
    }
    
    public function target()
    {
        $periode = DB::table("PeriodeSensus")->where('IsOpen', 'Y')->orderBy('Tahun', 'desc')->first()->Tahun ?? 0;
        // $aksesWilayah  = auth()->user()->AksesWilayah->first();
        $aksesWilayah  = collect(currentUser('AksesWilayah'))->first();
        $maxTargetKecamatan = DB::table('Target_KK_Kecamatan')->where('Periode_Sensus', $periode)->where('ID_Kecamatan', $aksesWilayah->id_kecamatan)->pluck('Target_KK')->first() ?? '-';
        $approved = (new Approval())->isApproved($aksesWilayah->id_kecamatan, 'target');
        // $row = DB::table('Mon_Wilayah_Kec')->where('Periode_Sensus', $periode)
        //     ->where('ID_Provinsi', $aksesWilayah->id_provinsi)
        //     ->where('ID_Kabupaten', $aksesWilayah->id_kabupaten)
        //     ->where('ID_Kecamatan', $aksesWilayah->id_kecamatan)
        //     ->first();
        // $approved = $row ? ($row->Status_Approve_Target ? true : false) : false; 
        $parameter = DB::table('Parameter')->whereIn('Group',['MaxTarget'])->pluck('Value','Code');
        return view('wilayah.targetkk')->with(compact('parameter', 'approved', 'maxTargetKecamatan'));
    }
    
    public function treeTarget()
    {
        $model = new Wilayah();
        $rows = $model->getTreeTarget();
        return $this->jsonOutput($rows);
    }
    
    public function updateTarget()
    {   
        $model = new Wilayah();
        $rows = $model->postTargetKK();
        // $rows = request()->post();
        return $this->jsonOutput($rows);
    }
    
    public function avprovinsi($parentid=0)
    {
        $sql = '
        SELECT id_provinsi as id, nama_provinsi as text FROM "Provinsi"
        WHERE id_provinsi NOT IN 
        (
        select id_provinsi from "User" 
        INNER JOIN "UserAkses" ON "User"."ID"="UserAkses"."UserID"
        INNER JOIN "Provinsi" ON "UserAkses"."WilayahID"="Provinsi".id_provinsi
        where "TingkatWilayahID"=1
        )';
        $rows = DB::select($sql);
        return $this->jsonOutput($rows);
    }
    
    public function avkabupaten($parentid=0)
    {
        $sql = '
        SELECT id_kabupaten as id, nama_kabupaten as text FROM "Kabupaten"
        where "id_provinsi"=?
        ';
        $rows = DB::select($sql,[$parentid]);
        return $this->jsonOutput($rows);
    }
    
    public function avkecamatan($parentid=0)
    {
        $sql = '
        SELECT id_kecamatan as id, nama_kecamatan as text FROM "Kecamatan"
        WHERE id_kecamatan NOT IN 
        (
        select id_kecamatan from "User" 
        INNER JOIN "UserAkses" ON "User"."ID"="UserAkses"."UserID"
        INNER JOIN "Kecamatan" ON "UserAkses"."WilayahID"="Kecamatan".id_kecamatan
        where "TingkatWilayahID"=3
        )
        AND  "Kecamatan".id_kabupaten=?
        ';
        $rows = DB::select($sql,[$parentid]);
        return $this->jsonOutput($rows);
    }
    
    public function avkelurahan($parentid=0)
    {
        $sql = '
        SELECT id_kelurahan as id, nama_kelurahan as text FROM "Kelurahan"
        WHERE id_kelurahan NOT IN 
        (
        select id_kelurahan from "User" 
        INNER JOIN "UserAkses" ON "User"."ID"="UserAkses"."UserID"
        INNER JOIN "Kelurahan" ON "UserAkses"."WilayahID"="Kelurahan".id_kelurahan
        where "TingkatWilayahID"=4
        )
        AND  "Kelurahan".id_kecamatan=?
        ';
        $rows = DB::select($sql,[$parentid]);
        return $this->jsonOutput($rows);
    }
}
