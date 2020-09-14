<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use App\Models\Response;
use App\Models\User;
use App\Models\Role;
use App\Models\TingkatWilayah;
use App\Models\Master\Kelurahan;
use App\Helpers\Helper;
use DB;
use Auth;
use Hash;
use Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:usermgmt')->only(['index','dataPaging','store','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameter = \DB::table('Parameter')->whereIn('Group',['Pwd'])->pluck('Value','Code');
        $m = new Kelurahan();
        $kelurahan = $m->getByUserID();
        
        $mr = new Role();
        $availroles = $mr->getRoleByLevel(currentUser('RoleLevel'));
        $maproletkwilayah = [
            2 => [1],
            3 => [3],
            4 => [4],
            5 => [6],
            6 => [3]
        ];
        $maproletkwilayah_json = json_encode($maproletkwilayah);
        return view('user.index')->with(compact('parameter','availroles','kelurahan', 'maproletkwilayah_json'));
    }

    public function dataPaging()
    {
        $id = currentUser('RoleID');
        $usermodel = new User();
        $users = $usermodel->getDataPagingUserWil();
        return $this->jsonOutput($users);
    }

    public function datatable()
    {
        $id = currentUser('RoleID');
        $usermodel = new User();
        $users = $usermodel->getUserDatatable();
        return $this->jsonOutput($users);
    }

    public function create()
    {
        $parameter = DB::table('Parameter')->whereIn('Group',['Pwd'])->pluck('Value','Code');
        
        // $mprov = new Provinsi();
        // $provinsi = $mprov->getByUserID();

        $mkel = new Kelurahan();
        $kelurahan = $mkel->getByUserID();

        $mr = new Role();
        $availroles = $mr->getRoleByLevel(currentUser('RoleLevel'));

        $roletk = $mr->with('tkwilayah')->orderBy('ID')->get()->toArray();
        $roletk_json = json_encode($roletk);

        $maproletkwilayah = [
            2 => [1],
            3 => [3],
            4 => [4],
            5 => [6],
            6 => [3]
        ];
        $maproletkwilayah_json = json_encode($maproletkwilayah);
        return view('user.create')->with(compact('parameter','availroles','kelurahan', 'roletk_json'));
    }

    public function save(Request $request)
    {
        $usermodel = new User();
        $result = $usermodel->postSaveUser();
        return $this->jsonOutput($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usermodel = new User();
        $result = $usermodel->postCreateUser();
        return $this->jsonOutput($result);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usermodel = new User();
        $result = $usermodel->postUpdateUser($id);
        return $this->jsonOutput($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $usermodel = new User();
        $result = $usermodel->postDeleteUser($id);
        return $this->jsonOutput($result);
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function updateProfile(Request $request, $id)
    {
        $usermodel = new User();
        $result = $usermodel->postUpdateProfile($id);
        return $this->jsonOutput($result);
    }
    
    public function showLoginForm()
    {
        if (auth()->check()) return redirect('/');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $usermodel = new User();
        $result = $usermodel->login($request);
        return ($result->status) ? redirect('/') : redirect()->to('login')
                    ->withInput($request->input())
                    ->withErrors($result->message);
    }

    public function logout(Request $request)
    {
        \Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    public function resetPassword(Request $request, $id)
    {
        $usermodel = new User();
        $result = $usermodel->postResetUser($id);
        return $this->jsonOutput($result);
    }

    public function changePassword()
    {
        $usermodel = new User();
        $result = $usermodel->postChangePassword();
        return $this->jsonOutput($result);
    }

    public function getRole()
    {
        $Roleid = currentUser('RoleLevel');
        $userRole = new Role();
        $result = $userRole->getRoleByLevel($Roleid);
        return $this->jsonOutput($result);
    }

    public function getTingkatWilayah($id)
    {
        $userWilayah = new TingkatWilayah();
        $result = $userWilayah->getDataListByRoleID($id);
        return $this->jsonOutput($result);
    }
    
    public function getcities()
    {   
        $result = [];
        $wilsess = (array)session()->get('user.AksesWilayah')[0];
        $kota = new \App\Models\Master\Kabupaten();
        $result = $kota->getByProvinsi($wilsess['id_provinsi']);
        return $this->jsonOutput($result);
    }
    
    public function importUser()
    {   
        if (currentUser('RoleID')>=3) abort(403);
        $roles = Role::whereIn('ID',[3,6])->get();
        // debug($roles);exit;
        return view('user.import')->with(compact('roles'));
    }
    
    public function processImport(Request $request)
    {
        
        \DB::table('User_Import')->where('CreatedBy',currentUser('UserName'))->delete();
        //$result = $request->file();
        $path = $request->file('file')->getRealPath();
        $file = file($path);
        $data = array_slice($file, 1);
        $rows = [];
        foreach ($data as $str) {
            $row = explode(';', $str);
            $item = [];
            $item['UserName'] = $row[0];
            $item['NamaLengkap'] = $row[1];
            $item['NIK'] = $row[2];
            $item['Alamat'] = $row[3];
            $item['NoTelepon'] = $row[4];
            $item['Email'] = $row[5];
            $item['NIP'] = $row[6];
            $item['WilayahID'] = $row[7];
            $item['CreatedBy'] = currentUser('UserName');
            $item['RoleID'] = $request->input('RoleID');
            $rows[] = $item;
            \DB::table('User_Import')->insert($item);
        }
        
        return $this->jsonOutput($rows);
    }
    
    public function processDataImport(Request $request)
    {   
        $rows = \DB::table('User_Import')->where('CreatedBy',currentUser('UserName'))->get();
        $data = [];
        $cnt = 0;
        foreach ($rows as $row) {
                $item = [];
                $item['ID'] = User::max('ID') + 1;
                $item['PeriodeSensus'] = \DB::table('PeriodeSensus')->where('IsOpen','Y')->first()->Tahun ?? '';
                $item['UserName'] = $row->UserName;
                $item['NamaLengkap'] = $row->NamaLengkap;
                $item['NIK'] = $row->NIK;
                $item['Alamat'] = $row->Alamat;
                $item['NoTelepon'] = $row->NoTelepon;
                $item['Email'] = $row->Email;
                $item['NIP'] = $row->NIP;
                $data[$cnt] = $item;
                $item['CreatedBy'] = currentUser('UserName');
                $item['Password'] = \Hash::make(\DB::table('Parameter')->where('Group','Pwd')->first()->Value ?? '');
                $item['RoleID'] = $row->RoleID;
                $item['TingkatWilayahID'] = 3;
                $item['IsTemporary'] = 0;
                $item['IsActive'] = 1;
            try {
                User::create($item);
                \DB::table('User_Import')->where('CreatedBy',currentUser('UserName'))->where('UserName',$row->UserName)->delete();
                $data[$cnt]['Keterangan'] = 'OK';
            } catch (\Exception $ex) {
                $data[$cnt]['Keterangan'] = $ex->getMessage();
            }
            $cnt++;
        }
        return $this->jsonOutput($data);
    }

    public function generateOTP () 
    {
        $otp = rand(0000,9999);
        $phone = Session::get('phone');
        $message = 'OTP BKKBN ' . $otp;
        $masked = $phone;
        // call sendSMS helper
        $jsonResponse = sendSMS($phone, $message);
        
        /* $jsonResponse = new Response();
        $userdata = array();
        $jsonResponse->status = true;
        $jsonResponse->message = "OTP dikirim ke nomor ".$masked;
        $userdata['phone'] = $masked;
        $userdata['OTP'] = $otp;
        $jsonResponse->data= $userdata;
        $jsonResponse = $jsonResponse->get(); */
        
        Session::put('otp', $otp);
        return $this->jsonOutput($jsonResponse);
       
    }

    public function checkMobileNumber($UserName) {

        $jsonResponse = new Response();
        $usermodel = new User();
        $result = $usermodel->getPhoneNumber($UserName);
        
        if (!$result ) {
            $jsonResponse->message = "Username tidak ditemukan, silakan periksa kembali username yang anda masukkan.";
            $jsonResponse->status = false;
        } else if ($result->NoTelepon == null || $result->NoTelepon == '' || empty($result->NoTelepon)) {
            $jsonResponse->message = "No Telepon untuk pengiriman OTP tidak ditemukan. Silakan hubungi Administrator.";
            $jsonResponse->status = false;
        } else {
            $number = $result->NoTelepon;
            $masked = substr($number, 0, 2) . str_repeat('*', strlen($number) - 5) . substr($number, -3);
            $jsonResponse->message = "OTP dikirim ke nomor ".$masked;
            $jsonResponse->status = true;
            $userdata['phone'] = $result->NoTelepon;
            Session::put('phone', $result->NoTelepon);
            Session::put('id', $result->ID);            
        }

        return $this->jsonOutput($jsonResponse->get());
     }


    public function verifikasiOTP($otp) {
         $jsonResponse = new Response();

        if ($otp == Session::get('otp')) {
            $jsonResponse->message = "Great, OTP Number Correct";
            $jsonResponse->status = true;   
            $userdata['ID'] = Session::get('id');   
            $jsonResponse->data= $userdata;       
        } else {
            $jsonResponse->message = "OTP Number Is Invalid, Please input the correct number from your phone!";
            $jsonResponse->status = false;            
        }
        
        return $this->jsonOutput($jsonResponse->get());
    }

    public function forgotPassword(Request $request, $id)
    {
        $usermodel = new User();
        $result = $usermodel->postResetUser($id);
        Session::forget('phone');
        Session::forget('id');
        Session::forget('otp');
        return $this->jsonOutput($result);
    }

    
    
}
