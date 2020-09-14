<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Libraries\DataTable;
use App\Libraries\KTDataTable;
use App\Libraries\jqGrid;

use Carbon\Carbon;

use Auth;
use Hash;
use DB;
use Session;

class User extends MyAuthenticatable
{
    use HasApiTokens, Notifiable;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    protected $table = 'User';
    protected $primaryKey = 'ID';
    protected $appends = ['AksesWilayah'];    
    protected $guarded  = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'LastModified';
    protected $hidden = ['Password'];

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'ID', 'RoleID');
    }

    public function wilayah()
    {
        return $this->hasOne('App\Models\TingkatWilayah', 'ID', 'TingkatWilayahID');
    }

    public function akses()
    {
        return $this->hasMany('App\Models\UserAkses', 'UserID');
    }

    public function login(Request $request)
    {
        $vrules = [
            'UserName' => 'required',
            'Password' => 'required',
        ];

        $vmessages = [
            'UserName.required' => 'Username / Email harus diisi.',
            'Password.required' => 'Password harus diisi.',
        ];

        try {
            $validator = Validator::make($request->all(), $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }

            $user = User::whereRaw('LOWER("UserName") = LOWER(?)', [$request->UserName])
                ->orWhereRaw('LOWER("Email") = LOWER(?)', [$request->UserName])
                ->first();
            $hashCheck = \Hash::check($request->Password, $user?$user->Password:'');
            if (empty($user) || !$hashCheck) {
                $this->jsonResponse->message = 'Akun akses tidak valid';
                return $this->jsonResponse->get();
            }
            if ($user->IsActive != true) {
                $this->jsonResponse->message = 'Akun akses tidak aktif';
                return $this->jsonResponse->get();
            }

            // Belum memiliki akses wilayah tidak boleh login, kecuali Administrator
            if ($user->akses->count() == 0 && $user->RoleID <> 1) {
                $this->jsonResponse->message = 'Akun tidak memiliki hak akses wilayah. Silakan hubungi Supervisor atau Administrator';
                return $this->jsonResponse->get();
            }

            // RoleID=5 Pendata tidak boleh login
            if ($user->RoleID == 5) {
                $this->jsonResponse->message = 'Akun tidak memiliki hak akses menggunakan aplikasi ini.';
                return $this->jsonResponse->get();
            }

            /* \Auth::login($user);
            $userdata = $user->toArray();
            $userdata['RoleLevel'] = $user->role->Level;
            $userdata['RoleName'] = $user->role->RoleName;
            $userdata['TingkatWilayahID'] = $user->TingkatWilayahID ?? 0;
            $userdata['TingkatWilayah'] = $user->wilayah->TingkatWilayah ?? '';
            $request->session()->put('user', $userdata); */
            
            // $user = User::find($ID);
            \Auth::login($user);
            $this->updateAuthSession($user->ID);
             
            $menumodel = new \App\Models\Menu();
            $menu = $menumodel->getTopNav(currentUser('RoleID'));

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Akses diijinkan';
            $this->jsonResponse->data = $userdata;
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
    private function updateAuthSession($ID) {
        $user = User::find($ID);
        // \Auth::login($user);


        $userdata = $user->toArray();
        $userdata['RoleLevel'] = $user->role->Level;
        $userdata['RoleName'] = $user->role->RoleName;
        $userdata['RoleNameID'] = $this->initials($user->role->RoleName);
        $userdata['TingkatWilayahID'] = $user->TingkatWilayahID ?? 0;
        $userdata['TingkatWilayah'] = $user->wilayah->TingkatWilayah ?? '';
        request()->session()->put('user', $userdata);
    }

    public function getDataPaging()
    {
        $collection = $this->with(['role','wilayah'])->orderby('ID', 'desc');
        $data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $users = $data->get();
        return $users;
    }

    public function getDataPagingbyUser($id)
    {
        $RoleID = currentUser('RoleID');
        $Wilayahid = currentUser('TingkatWilayahID');
        $Id = currentUser('ID');
        
        if ($RoleID <> 1) {
            $collection = $this
                ->whereHas('role', function($q){
                    $q->where('Level', '>', currentUser('RoleLevel'));
                })
                ->with(['role','wilayah','akses'])
                ->where('TingkatWilayahID', '>=', $Wilayahid)
                ->where('CreatedBy', currentUser('UserName'))
                ->orderby('ID', 'asc');
                    
        } else {
            $collection = $this
                ->whereHas('role', function($q){
                    $q->where('Level', '>', currentUser('RoleLevel'));
                })
                ->with(['role','wilayah','akses'])
                ->where('TingkatWilayahID', '>=', $Wilayahid)
                ->orderby('ID', 'asc');
        }

        $data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $users = $data->get();
        return $users;
    }

    public function getDataPagingUserWil()
    {
        $TingkatWilayahID = currentUser('TingkatWilayahID');
        $WilayahID = auth()->user()->akses->pluck('WilayahID');
        $mwil = new \App\Models\Master\RT();
        
        $groupwil = [
            1 => 'ProvinsiID',
            2 => 'KabupatenID',
            3 => 'KecamatanID',
            4 => 'KelurahanID',
            5 => 'id_rw',
            6 => 'id_rt',
        ];
        
        if ($TingkatWilayahID > 0) {
            $Wilayah = $mwil->whereIn($groupwil[$TingkatWilayahID], $WilayahID);
            
            $sqlarray = [];
            for ($cnt=1; $cnt<=6; $cnt++) {
                $whereIn = implode(',',$Wilayah->groupBy($groupwil[$cnt])->pluck($groupwil[$cnt],$groupwil[$cnt])->toArray());
                
                $sql = 'SELECT DISTINCT u."ID", u."PeriodeSensus", u."UserName", u."NamaLengkap", u."NIK", u."Alamat", u."NoTelepon", u."Email", u."NIP", u."RoleID", u."TingkatWilayahID", r."RoleName", u."KabupatenKotaID", u."Smartphone", u."CreatedDate", u."IsActive"   
                , (SELECT COUNT(*) FROM "UserAkses" a WHERE a."UserID"=u."ID") as cntwil
                , tw."TingkatWilayah"
                FROM "User" u 
                LEFT JOIN "UserAkses" a ON u."ID"=a."UserID"
                INNER JOIN "Role" r ON u."RoleID"=r."ID"
                LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"
                Where u."TingkatWilayahID" > '.$TingkatWilayahID.' 
                AND u."TingkatWilayahID" = '.$cnt.' 
                AND (a."WilayahID" IN ('.$whereIn.') OR (u."CreatedBy" = \''. currentUser('UserName') .'\')) 
                ';
                $sqlarray[] = $sql;
            }
            $sql = implode(' UNION ', $sqlarray);
        } else {
            $sql = 'SELECT DISTINCT u."ID", u."PeriodeSensus", u."UserName", u."NamaLengkap", u."NIK", u."Alamat", u."NoTelepon", u."Email", u."NIP", u."RoleID", u."TingkatWilayahID", r."RoleName" , u."KabupatenKotaID", u."Smartphone", u."CreatedDate", u."IsActive" 
                , (SELECT COUNT(*) FROM "UserAkses" a WHERE a."UserID"=u."ID") as cntwil
                , tw."TingkatWilayah"
                FROM "User" u 
                LEFT JOIN "UserAkses" a ON u."ID"=a."UserID"
                INNER JOIN "Role" r ON u."RoleID"=r."ID"
                LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"
                Where u."TingkatWilayahID" = 1 
                OR (u."CreatedBy" = \''. currentUser('UserName') .'\')
                
                ';
        }
        // $rows = DB::select($sql);
        $data = new KTDatatable($sql, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $rows = $data->get();
        return $rows;
    }

    public function getUserDatatable()
    {
        $TingkatWilayahID = currentUser('TingkatWilayahID');
        $WilayahID = collect(currentUser('akses'))->pluck('WilayahID');
        $mwil = new \App\Models\Master\RT();
        
        $groupwil = [
            1 => 'ProvinsiID',
            2 => 'KabupatenID',
            3 => 'KecamatanID',
            4 => 'KelurahanID',
            5 => 'id_rw',
            6 => 'id_rt',
        ];

        
        if ($TingkatWilayahID > 0) {
            $Wilayah = $mwil->whereIn($groupwil[$TingkatWilayahID], $WilayahID);
            
            $sqlarray = [];
            for ($cnt=$TingkatWilayahID+1; $cnt<=6; $cnt++) {
                

                $whereIn = implode(',',$Wilayah->groupBy($groupwil[$cnt])->pluck($groupwil[$cnt],$groupwil[$cnt])->toArray());
                
                $sql = 'SELECT DISTINCT u."ID", u."PeriodeSensus", u."UserName", u."NamaLengkap", u."NIK", u."Alamat", u."NoTelepon", u."Email", u."NIP", u."RoleID", u."TingkatWilayahID", r."RoleName", u."KabupatenKotaID", u."Smartphone", u."CreatedDate", u."IsActive"   
                , (SELECT COUNT(*) FROM "UserAkses" a WHERE a."UserID"=u."ID") as cntwil
                , tw."TingkatWilayah"
                , v.nama_kelurahan, v.nama_rw, v.nama_rt
                FROM "User" u 
                LEFT JOIN "UserAkses" a ON u."ID"=a."UserID"
                INNER JOIN "Role" r ON u."RoleID"=r."ID"
                LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"
                LEFT JOIN v_user_tk_'.$cnt.'_agg v ON u."ID"=v.id_user
                Where u."TingkatWilayahID" > '.$TingkatWilayahID.' 
                AND u."TingkatWilayahID" = '.$cnt.' 
                AND (a."WilayahID" IN ('.$whereIn.') OR (u."CreatedBy" = \''. currentUser('UserName') .'\')) 
                
                ';
                if (request()->input('StatusWilayah')=='0') {
                    $sql = 'SELECT * FROM ('.$sql.') s  WHERE cntwil=0 ';
                } elseif (request()->input('StatusWilayah')=='1') {
                    
                    $sql = 'SELECT * FROM ('.$sql.') s WHERE s.cntwil>0 ';

                    $whereID = ' AND 1=1 ';
                    if (!empty(request()->input('RT'))) {
                        $findID = DB::table('v_user_tk_'.$cnt)->where('id_rt',request()->input('RT'))->get();
                        $ids = $findID->isEmpty() ? 0 :  implode(',', $findID->pluck('id_user')->toArray());
                        $whereID = ' AND s."ID" IN ('.$ids.') ';
                    } elseif (!empty(request()->input('RW'))) {
                        $findID = DB::table('v_user_tk_'.$cnt)->where('id_rw',request()->input('RW'))->get();
                        $ids =  $findID->isEmpty() ? 0 :  implode(',', $findID->pluck('id_user')->toArray());
                        $whereID =' AND s."ID" IN ('. $ids.') ';
                    } elseif (!empty(request()->input('Kelurahan'))) {
                        $findID = DB::table('v_user_tk_'.$cnt)->where('id_kelurahan',request()->input('Kelurahan'))->get();
                        $ids = $findID->isEmpty() ? 0 : implode(',', $findID->pluck('id_user')->toArray());
                        $whereID = ' AND s."ID" IN ('. $ids.') ';
                    }
                    $sql .= $whereID;
                }
                $sqlarray[] = $sql;
            }
            $sql = implode(' UNION ', $sqlarray);
        } else {
            $sql = 'SELECT DISTINCT u."ID", u."PeriodeSensus", u."UserName", u."NamaLengkap", u."NIK", u."Alamat", u."NoTelepon", u."Email", u."NIP", u."RoleID", u."TingkatWilayahID", r."RoleName" , u."KabupatenKotaID", u."Smartphone", u."CreatedDate", u."IsActive" 
                , (SELECT COUNT(*) FROM "UserAkses" a WHERE a."UserID"=u."ID") as cntwil
                , tw."TingkatWilayah"
                FROM "User" u 
                LEFT JOIN "UserAkses" a ON u."ID"=a."UserID"
                INNER JOIN "Role" r ON u."RoleID"=r."ID"
                LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"
                Where u."TingkatWilayahID" = 1 
                OR (u."CreatedBy" = \''. currentUser('UserName') .'\')
                
                ';
        }
        
        $rows = DB::select($sql);
        $data = new Datatable($sql, ['searchFields'=>['UserName', 'NamaLengkap', 'Email']]);
        $rows = $data->get();
        return $rows;
    }

    public function getStatPendata()
    {
        $TingkatWilayahID = currentUser('TingkatWilayahID');
        $WilayahID = auth()->user()->akses->pluck('WilayahID');
        $mwil = new \App\Models\Master\RT();
        
        $groupwil = [
            1 => 'ProvinsiID',
            2 => 'KabupatenID',
            3 => 'KecamatanID',
            4 => 'KelurahanID',
            5 => 'id_rw',
            6 => 'id_rt',
        ];
        
        if ($TingkatWilayahID > 0) {
            $Wilayah = $mwil->whereIn($groupwil[$TingkatWilayahID], $WilayahID);
            
            $sqlarray = [];
            for ($cnt=5; $cnt<=6; $cnt++) {
                $whereIn = implode(',',$Wilayah->groupBy($groupwil[$cnt])->pluck($groupwil[$cnt],$groupwil[$cnt])->toArray());
                
                $sql = 'SELECT u."TingkatWilayahID", count(*) as total
                FROM "User" u 
                LEFT JOIN "UserAkses" a ON u."ID"=a."UserID"
                INNER JOIN "Role" r ON u."RoleID"=r."ID"
                LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"
                Where u."TingkatWilayahID" > '.$TingkatWilayahID.' 
                AND u."TingkatWilayahID" = '.$cnt.' 
                AND (a."WilayahID" IN ('.$whereIn.') OR (u."CreatedBy" = \''. currentUser('UserName') .'\')) 
                GROUP BY u."TingkatWilayahID"
                
                ';
                $sqlarray[] = $sql;
            }
            $sql = implode(' UNION ', $sqlarray);
        } else {
            $sql = 'SELECT u."TingkatWilayahID", count(*) as total
                FROM "User" u 
                LEFT JOIN "UserAkses" a ON u."ID"=a."UserID"
                INNER JOIN "Role" r ON u."RoleID"=r."ID"
                LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"
                Where u."TingkatWilayahID" IN (5,6) 
                OR (u."CreatedBy" = \''. currentUser('UserName') .'\')
                GROUP BY u."TingkatWilayahID"
                
                ';
        }
        return $sql;
    }

    public function getDataPagingHirarki($id)
    {
        $RoleID = currentUser('RoleID');
        $Wilayahid = currentUser('TingkatWilayahID');
        $UserID = currentUser('ID');
        
        if ($RoleID <> 1) {
            $collection = 'SELECT u."ID", u."PeriodeSensus", u."UserName", u."NamaLengkap", u."NIK", u."Alamat", u."NoTelepon", u."Email", u."NIP", u."RoleID", u."TingkatWilayahID"
             , (SELECT COUNT(*) FROM "UserAkses" a WHERE a."UserID"=u."ID") as cntwil
 , tw."TingkatWilayah"
            FROM (

            WITH RECURSIVE nodes("ID", "PeriodeSensus", "UserName") AS (
                SELECT s1.*
                FROM "User" s1 WHERE "CreatedBy" = (SELECT "UserName" FROM "User" WHERE "ID"='.$UserID.')
                    
                    UNION
                
                    SELECT s2.*
                FROM "User" s2
                    INNER JOIN nodes s1 ON s2."CreatedBy" = CAST(s1."UserName" as varchar)
            )
            SELECT * FROM nodes order by "UserName"
            ) u
LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"';
                    
        } else {
            $collection = 'SELECT u."ID", u."PeriodeSensus", u."UserName", u."NamaLengkap", u."NIK", u."Alamat", u."NoTelepon", u."Email", u."NIP", u."RoleID", u."TingkatWilayahID"
             , (SELECT COUNT(*) FROM "UserAkses" a WHERE a."UserID"=u."ID") as cntwil
 , tw."TingkatWilayah"
            FROM "User" u
LEFT JOIN "TingkatWilayah" tw ON tw."ID"=u."TingkatWilayahID"';
        }

        $data = new jqGrid($collection, ['searchFields'=>['UserName', 'NamaLengkap']]);
        $users = $data->get();
        return $users;
    }
    
    public function getShowUser($id)
    {
        try {
            $user = $this->find($id);
            if (is_null($user)) {
                $this->jsonResponse->message = 'Data tidak ditemukan';
            } else {
                $this->jsonResponse->status = true;
                $this->jsonResponse->data = $user;
            }
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postSaveUser()
    {
        $request = request()->all();
        $request['Password'] = \DB::table('Parameter')->where('Group','Pwd')->first()->Value ?? '';

        $vrules = [
            'UserName' => 'required|unique:'.$this->table,
            'NamaLengkap' => 'required',
            'Email' => 'required|unique:'.$this->table,
            'Password' => 'required',
            'RoleID' => 'required',
            'TingkatWilayahID' => 'required',
            'NoTelepon' => 'required',
        ];

        $vmessages = [
            'UserName.required' => 'Username harus diisi.',
            'UserName.alpha_num' => 'Username hanya boleh alphanumeric [a-z|A-Z|0-9].',
            'UserName.unique' => 'Username sudah ada.',
            'NamaLengkap.required' => 'Nama Lengkap harus diisi.',
            'Email.required' => 'Email harus diisi.',
            'Email.unique' => 'Email sudah ada.',
            'Password.required' => 'Password harus diisi.',
            'RoleID.required' => 'Role harus dipilih.',
            'TingkatWilayahID.required' => 'Tingkat Wilayah harus dipilih.',
            'NoTelepon.required' => 'No Telepon harus diisi.',
        ];
        try {
            
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }

            $maxValue = User::orderBy('ID', 'desc')->value('ID');
            $plain_password = $request['Password'];

            $request['ID'] = $maxValue + 1; //\DB::raw("nextval('\"Users_ID_seq\"')");
            $request['Password'] = \Hash::make($plain_password);
            $request['NIK'] = $request['NIK'] ?? null;
            $request['Alamat'] = $request['Alamat'] ?? null;
            $request['NIP'] = $request['NIP'] ?? null;
            $request['IsTemporary'] = 0;
            $request['IsActive'] = 1;
            $request['PeriodeSensus'] = '2020';
            $request['CreatedBy'] = currentUser('UserName');
            $request['LastModifiedBy'] = currentUser('UserName');
            $request['Smartphone'] = !empty($request['Smartphone']) ? true : false;
            $user = $this->create($request);
            
            // call MW API 
            // $this->callMWUpdatePwd($user->UserName, $plain_password);
            
            
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postCreateUser()
    {
        $request = request()->all();
        $request['Password'] = \DB::table('Parameter')->where('Group','Pwd')->first()->Value ?? '';

        $vrules = [
            'UserName' => 'required|unique:'.$this->table,
            'NamaLengkap' => 'required',
            'Email' => 'required|unique:'.$this->table,
            'Password' => 'required',
            'RoleID' => 'required',
            'TingkatWilayahID' => 'required',
            'NoTelepon' => 'required',
        ];

        $vmessages = [
            'UserName.required' => 'Username harus diisi.',
            'UserName.alpha_num' => 'Username hanya boleh alphanumeric [a-z|A-Z|0-9].',
            'UserName.unique' => 'Username sudah ada.',
            'NamaLengkap.required' => 'Nama Lengkap harus diisi.',
            'Email.required' => 'Email harus diisi.',
            'Email.unique' => 'Email sudah ada.',
            'Password.required' => 'Password harus diisi.',
            'RoleID.required' => 'Role harus dipilih.',
            'TingkatWilayahID.required' => 'Tingkat Wilayah harus dipilih.',
            'NoTelepon.required' => 'No Telepon harus diisi.',
        ];
        try {
            
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }

            $maxValue = User::orderBy('ID', 'desc')->value('ID');
            $plain_password = $request['Password'];

            $request['ID'] = $maxValue + 1; //\DB::raw("nextval('\"Users_ID_seq\"')");
            $request['Password'] = \Hash::make($plain_password);
            $request['NIK'] = $request['NIK'] ?? null;
            $request['Alamat'] = $request['Alamat'] ?? null;
            // $request['KabupatenKotaID'] = $request['KabupatenKotaID'] ?? null;
            $request['NIP'] = $request['NIP'] ?? null;
            $request['IsTemporary'] = 0;
            $request['IsActive'] = 1;
            $request['PeriodeSensus'] = '2020';
            $request['CreatedBy'] = currentUser('UserName');
            $request['LastModifiedBy'] = currentUser('UserName');
            $request['Smartphone'] = !empty($request['Smartphone']) ? true : false;
            $user = $this->create($request);
            
            // call MW API 
            // $this->callMWUpdatePwd($user->UserName, $plain_password);
            
            
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postUpdateUser($id)
    {
        $request = request()->all();

        $vrules = [
            'NamaLengkap' => 'required',
            'Email' => 'required|unique:User,Email,'.$id.',ID',
            'NoTelepon' => 'required',
        ];

        $vmessages = [
            'NamaLengkap.required' => 'Nama Lengkap harus diisi.',
            'Email.required' => 'Email harus diisi.',
            'Email.unique' => 'Email sudah ada.',
            'NoTelepon.required' => 'No Telepon harus diisi.',
        ];

        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }
            
            $request['LastModified'] = date('Y-m-d H:i:s');
            $request['LastModifiedBy'] = currentUser('UserName');
            
            $user = $this->find($id);
            $user->NamaLengkap = $request['NamaLengkap'];
            $user->NIK = $request['NIK'];
            $user->Alamat = $request['Alamat'];
            // $user->KabupatenKotaID = $request['KabupatenKotaID'];
            $user->NoTelepon = $request['NoTelepon'];
            $user->Email = $request['Email'];
            $user->NIP = $request['NIP'] ?? '';
            $user->RoleID = $request['RoleID'];
            $user->TingkatWilayahID = $request['TingkatWilayahID'];
            $user->LastModified = $request['LastModified'];
            $user->LastModifiedBy = $request['LastModifiedBy'];
            $user->Smartphone = !empty($request['Smartphone']) ? true : false;
            $row = $user->save();
            
            // $this->updateAuthSession($user->ID);
            
            
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postUpdateProfile($id)
    {
        $request = request()->all();

        $vrules = [
            'NamaLengkap' => 'required',
            'Email' => 'required|unique:User,Email,'.$id.',ID',
            'NoTelepon' => 'required',
        ];

        $vmessages = [
            'NamaLengkap.required' => 'Nama Lengkap harus diisi.',
            'Email.required' => 'Email harus diisi.',
            'Email.unique' => 'Email sudah ada.',
            'NoTelepon.required' => 'No Telepon harus diisi.',
        ];

        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }
            
            $request['LastModified'] = date('Y-m-d H:i:s');
            $request['LastModifiedBy'] = currentUser('UserName');
            
            $user = $this->find($id);
            $user->NamaLengkap = $request['NamaLengkap'];
            $user->NIK = $request['NIK'];
            $user->Alamat = $request['Alamat'];
            // $user->KabupatenKotaID = $request['KabupatenKotaID'];
            $user->NoTelepon = $request['NoTelepon'];
            $user->Email = $request['Email'];
            $user->NIP = $request['NIP'] ?? '';
            $user->LastModified = $request['LastModified'];
            $user->LastModifiedBy = $request['LastModifiedBy'];
            $row = $user->save();
            
            $this->updateAuthSession($user->ID);
            
            
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postResetUser($id)
    {
        $request = request()->all();

        $vrules = [
          'Password' => 'required',
          'rePassword' => 'required|same:Password',
        ];

        $vmessages = [
            'Password.required' => 'Mohon Isi dulu password baru!',
            'rePassword.required' => 'Mohon Isi dulu Confirmation password!',
            'rePassword.same'  => 'Check Ulang Password yang anda input tidak sama!',
        ];

        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }
            
            $plain_password = $request['Password'];

            $requests['Password'] = Hash::make($plain_password);
            $requests['LastModified'] = date('Y-m-d H:i:s');
            $requests['LastModifiedBy'] = currentUser('UserName');
            $user = $this->find($id);
            $user->fill($requests);
            $row = $user->save();

            // send reset password user email
            $this->sendResetEmail($user->ID, $request['Password']);
            
            // call MW API 
            $this->callMWUpdatePwd($user->UserName, $plain_password);
            
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan, Silahkan Login Ulang.!';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }

    public function postChangePassword()
    {
        $request = request()->all();

        $vrules = [
          'oldpassword' => 'required',
          'newpassword' => 'required',
          'newpasswordconfirm' => 'required|same:newpassword',
        ];

        $vmessages = [
            'oldpassword.required' => 'Password lama harus diisi',
            'newpassword.required' => 'Password baru harus diisi',
            'newpasswordconfirm.required' => 'Konfirmasi Password baru harus diisi',
            'newpasswordconfirm.same' => 'Konfirmasi Password baru harus sama',
        ];
        
        try {
            $validator = Validator::make($request, $vrules, $vmessages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                $this->jsonResponse->message = $error;
                return $this->jsonResponse->get();
            }            
            $user = $this->find(currentUser('ID'));
            $hashCheck = Hash::check($request['oldpassword'], $user?$user->Password:'');
            if (!$hashCheck) {
                $this->jsonResponse->message = 'Password lama salah';
                return $this->jsonResponse->get();
            }
            $plain_password = $request['newpassword'];
            $user->Password = Hash::make($plain_password);
            $user->LastModified = date('Y-m-d H:i:s');
            $user->LastModifiedBy = currentUser('UserName');
            $row = $user->save();

            // call MW API 
            $this->callMWUpdatePwd($user->UserName, $plain_password);

            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan, Silahkan Login Ulang.!';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
    public function sendResetEmail($userID, $password)
    {
        $user = User::with(['role','wilayah'])->find($userID);
        $spv = User::with(['role','wilayah'])->find(currentUser('ID'));
        $subject = 'Reset Password User BKKBN PK2020';

        //debug($user->Email); exit;

        
        $body = '';
        $body .= '<p>Hai <strong>' . $user->NamaLengkap . '</strong>,</p>';
        $body .= '<p>&nbsp;</p>';
        $body .= '<p>Password anda telah direset' . (is_null($spv) ? '' : 'oleh Supervisor') . '. Untuk mengakses aplikasi silahkan buka <a href="#">link berikut<a>. Dengan login: <br />';
        $body .= '  - username: ' . $user->UserName . ' <br />';
        $body .= '  - password: ' . $password . '<br />';
        $body .= '</p>';
        $body .= '<p>Untuk keamanan silakan melakukan perubahan password (User Profile) yang disediakan pada menu aplikasi.</p>';
        $body .= '<p>&nbsp;</p>';
        $body .= '<p>TTD</p>';
        $body .= '<p>';
        if (!is_null($spv)) {
                $body .= $spv->NamaLengkap . '<br/>';
                $body .= $spv->role->RoleName . ' wilayah ' . ($spv->AksesWilayah[0]->fullwilayahrev ?? '');            
        }

        $body .= '</p>';
        $emailSent = json_decode($user->EmailSent, true);
        
        if (!is_null($spv)) { 
            $result = $this->sendMail($user->Email, $spv->Email, $subject, $body);
        } else {
             $result = $this->sendMail($user->Email, null , $subject, $body);
        }

        if ($result->status) {
            $emailSent['2'] = ['date' => date('Y-m-d H:i:s')];
        } else {                
            $emailSent['2'] = ['errmsg' => $result->message];
        }
        $jsonstr = json_encode($emailSent);
        User::where('ID', $userID)->update(['EmailSent'=>$jsonstr]);
    }
    
    public function sendNewUserEmail($userID)
    {
        $user = User::with(['role','wilayah'])->find($userID);
        $spv = User::with(['role','wilayah'])->find(currentUser('ID'));
        $password = DB::table('Parameter')->where('Group','Pwd')->first()->Value ?? '';
        
        $subject = 'User Baru BKKBN PK2020';
        
        $body = '';
        $body .= '<p>Selamat bergabung <strong>' . $user->NamaLengkap . '</strong>,</p>';
        $body .= '<p>&nbsp;</p>';
        $body .= '<p>Anda terdaftar sebagai user ' . $user->role->RoleName . ' dengan tingkat wilayah ' . $user->wilayah->TingkatWilayah . '. Berikut adalah data wilayah dalam lingkup tugas anda:</p>';
        $body .= '<ul>';
        foreach ($user->AksesWilayah as $row) {
        $body .= '<li>' . $row->fullwilayahrev . '</li>';
        }
        $body .= '</ul>';
        $body .= '';
        $body .= '<p>Untuk mengakses aplikasi silahkan buka <a href="#">link berikut<a>. Dengan login: <br />';
        $body .= '  - username: ' . $user->UserName . ' <br />';
        $body .= '  - password: ' . $password . '<br />';
        $body .= '</p>';
        $body .= '<p>Untuk keamanan silakan melakukan perubahan password (User Profile) yang disediakan pada menu aplikasi.</p>';
        $body .= '<p>&nbsp;</p>';
        $body .= '<p>TTD</p>';
        $body .= '<p>';
        $body .= $spv->NamaLengkap . '<br/>';
        $body .= $spv->role->RoleName . ' wilayah ' . ($spv->AksesWilayah[0]->fullwilayahrev ?? '');
        $body .= '</p>';
        $emailSent = json_decode($user->EmailSent, true);
        if (empty($emailSent[1]['date'])) {
            $result = $this->sendMail($user->Email, $spv->Email, $subject, $body);
            if ($result->status) {
                $emailSent['1'] = ['date' => date('Y-m-d H:i:s')];
            } else {                
                $emailSent['1'] = ['errmsg' => $result->message];
            }
            $jsonstr = json_encode($emailSent);
            User::where('ID', $userID)->update(['EmailSent'=>$jsonstr]);
        }
    }
    

    public function sendMail($to_email, $bcc_email, $subject, $body)
    {
        //--- send mail reset password
        // toEmail diisi email User yg di reset
        // bccEmail diisi email Supervisor yg mereset
        
        $mail = new \App\Libraries\PKMail();
        $mail->subject = $subject;
        $mail->body = $body;
        $mail->toEmail = $to_email; // bisa string, bisa array of string email
        $mail->bccEmail = $bcc_email; //optional, bisa string, bisa array of string email
        $result = $mail->send();
        return $result;
    }

    public function postDeleteUser($id)
    {
        try {
            $user = $this->find($id);
            if (is_null($user)) {
                $this->jsonResponse->message = 'Data tidak ditemukan';
            } else {
                $user->IsActive = 0;
                $user->LastModified = date('Y-m-d H:i:s');
                $user->LastModifiedBy = currentUser('UserName');
                $user->save();
                $this->jsonResponse->status = true;
                $this->jsonResponse->message = 'Data berhasil dihapus';
            }
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }


    public function getAksesWilayahAttribute($value)
    {
        $userakses = $this->akses->pluck('WilayahID');
        $akseswilayah = collect();
        switch ($this->TingkatWilayahID) {
            case 1: // Provinsi
                $akseswilayah = DB::table('v_provinsi')->whereIn('id_provinsi', $userakses)->get();
                break;
            case 2: // Kabupaten
                $akseswilayah = DB::table('v_kabupaten')->whereIn('id_kabupaten', $userakses)->get();
                break;
            case 3: // Kecamatan
                $akseswilayah = DB::table('v_kecamatan')->whereIn('id_kecamatan', $userakses)->get();
                break;
            case 4: // Kelurahan
                $akseswilayah = DB::table('v_kelurahan')->whereIn('id_kelurahan', $userakses)->get();
                break;
            case 5: // RW
                $akseswilayah = DB::table('v_rw')->whereIn('id_rw', $userakses)->get();
                break;
            case 6: // RT
                $akseswilayah = DB::table('v_rt')->whereIn('id_rt', $userakses)->get();
                break;
                
        }
        return $akseswilayah;
    }
    
    public function childPendata()
    {
        switch (currentUser('TingkatWilayahID')) {
            case 1 :
                $wilayah = auth()->user()->AksesWilayah->pluck('id_provinsi')->toArray();
                $ids = implode(",",$wilayah);
                $sql = 'SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=5
                WHERE "WilayahID" IN (
                SELECT distinct id_rw from v_data_wilayah_all where id_provinsi IN ('.$ids.')
                )

                UNION ALL

                SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=6
                WHERE "WilayahID" IN (
                SELECT distinct id_rt from v_data_wilayah_all where id_provinsi IN ('.$ids.')
                )';
                break;
                
            case 2 :
                $wilayah = auth()->user()->AksesWilayah->pluck('id_kabupaten')->toArray();
                $ids = implode(",",$wilayah);
                $sql = 'SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=5
                WHERE "WilayahID" IN (
                SELECT distinct id_rw from v_data_wilayah_all where id_kabupaten IN ('.$ids.')
                )

                UNION ALL

                SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=6
                WHERE "WilayahID" IN (
                SELECT distinct id_rt from v_data_wilayah_all where id_kabupaten IN ('.$ids.')
                )';
                break;
                
            case 3 :
                $wilayah = auth()->user()->AksesWilayah->pluck('id_kecamatan')->toArray();
                $ids = implode(",",$wilayah);
                $sql = 'SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=5
                WHERE "WilayahID" IN (
                SELECT distinct id_rw from v_data_wilayah_all where id_kecamatan IN ('.$ids.')
                )

                UNION ALL

                SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=6
                WHERE "WilayahID" IN (
                SELECT distinct id_rt from v_data_wilayah_all where id_kecamatan IN ('.$ids.')
                )';
                break;
                
            case 4 :
                $wilayah = auth()->user()->AksesWilayah->pluck('id_kelurahan')->toArray();
                $ids = implode(",",$wilayah);
                $sql = 'SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=5
                WHERE "WilayahID" IN (
                SELECT distinct id_rw from v_data_wilayah_all where id_kelurahan IN ('.$ids.')
                )

                UNION ALL

                SELECT DISTINCT b."ID", b."UserName", b."NamaLengkap"
                FROM "UserAkses" a
                INNER JOIN "User" b ON a."UserID"=b."ID" AND b."TingkatWilayahID"=6
                WHERE "WilayahID" IN (
                SELECT distinct id_rt from v_data_wilayah_all where id_kelurahan IN ('.$ids.')
                )';
                break;
                
            case 5 :
                break;
                
            case 6 :
                break;
                
        }
        $userPendata =  \DB::select($sql);
        return $userPendata;
    }
    
    public function callMWUpdatePwd($UserName, $Password)
    {
        $str = '';
        try {
            $client = new \GuzzleHttp\Client();
            $url = config('app.pk_mw_url') . '/resetpassword';
            $data = [ "name"=>$UserName, "password"=>$Password];
            $request = $client->post($url,  ['json'=>$data]);
            $body = $request->getBody();
            $str = (string) $body;
        } 
        catch (\Exception $ex) {
            logError('MWUpdatePwd', json_encode($data), $ex->getMessage());
        }
    }

    public function getPhoneNumber($username) {
        $user = User::select('ID','NoTelepon')->whereRaw('LOWER("UserName") = LOWER(?)', [$username])
                ->orWhereRaw('LOWER("Email") = LOWER(?)', [$username])
                ->first();
        // $sql = 'select "ID", "NoTelepon" from "User" where "UserName" = \''.$username.'\' limit 1';
        // $data =  \DB::select($sql);        
        return $user;
    }


    public function getReminder() {
        $today = Carbon::now();
        $reminder = null;
        $rows = DB::table('Parameter')->where('Group','=','Reminder')->orderBy('Code','asc')->get();
        foreach ($rows as $row) {
            try {
                $arrdate = explode('|',$row->Description);       
                if ($today->between(trim($arrdate[0]), trim($arrdate[1]))) {
                    $reminder =  $row->Value;
                }
            } catch(\Exception $e) {

            }
        }
        return $reminder;
    }

    function initials($str) {
        // cari inisial Role
        $ret = '';
        foreach (explode(' ', $str) as $word)
            $ret .= strtoupper($word[0]);
        return $ret;
    }
    
}
