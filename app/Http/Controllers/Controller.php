<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /* public function __construct()
    {
        // if (empty(session('usermenu'))) {
            
            // $menumodel = new \App\Models\Menu();
            // $menu = $menumodel->getTopNav(currentUser('role_id'));
            // request()->session()->put('usermenu', $menu);
        // }
    } */
    
    public function jsonOutput($obj)
    {
        return response()->json($obj, http_response_code());
    }
    
}
