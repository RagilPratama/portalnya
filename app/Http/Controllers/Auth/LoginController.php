<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

public function username()
{
    return 'UserName';
}
/* public function password()
{
    return '"Password"';
} */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'Password' => 'required|string',
        ]);
    }
    
    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? 'Email'
            : $this->username();
        return [
            $field => $request->get($this->username()),
            'Password' => $request->password,
        ];
        // return $request->only($this->username(), 'password');
    }
    
    protected function authenticated(Request $request, $user)
    {
        /* $role = $user->roles[0] ?? [];
        $role_id = $role->id ?? '';
        $role_name = $role->name ?? '';
        $provinsi_id = $role->pivot->provinsi_id ?? '';
        $provinsi_name = \App\Models\Wilayah::where('kode','=', $provinsi_id)->pluck('nama')->first();
        $kota_id = $role->pivot->kota_id ?? '';
        $kota_name = \App\Models\Wilayah::where('kode','=', $kota_id)->pluck('nama')->first();
        $kecamatan_id = $role->pivot->kecamatan_id ?? '';
        $kecamatan_name = \App\Models\Wilayah::where('kode','=', $kecamatan_id)->pluck('nama')->first();
        $kelurahan_id = $role->pivot->kelurahan_id ?? '';
        $kelurahan_name = \App\Models\Wilayah::where('kode','=', $kelurahan_id)->pluck('nama')->first();
        $area_id = $role->pivot->area_id ?? '';
        $area_name = '';
        if (!empty($provinsi_name)) {
            $area_name .= $provinsi_name;
            if (!empty($kota_name)) {
                $area_name .= ' - '.$kota_name;
                if (!empty($kecamatan_name)) {
                    $area_name .= ' - '.$kecamatan_name;
                }
            }
        }
        $userdata = [
            'id' => $user->id,
            'name' => $user->name,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'role_name' => $role_name,
            'role_id' => $role_id,
            'area_id' => $area_id,
            'area_name' => $area_name,
            'provinsi_id' => $provinsi_id,
            'provinsi_name' => $provinsi_name,
            'kota_id' => $kota_id,
            'kota_name' => $kota_name,
            'kecamatan_id' => $kecamatan_id,
            'kecamatan_name' => $kecamatan_name,
            'kelurahan_id' => $kelurahan_id,
            'kelurahan_name' => $kelurahan_name,
        ];
        $request->session()->put('user', $userdata);
        
        
        $menumodel = new \App\Models\Menu();
        $menu = $menumodel->getTopNav($role_id);
        $request->session()->put('usermenu', $menu); */
    }
}
