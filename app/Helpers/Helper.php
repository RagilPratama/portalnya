<?php
function debug($obj, $desc=1, $exit=0)
{
    echo '<pre>';
    if ($desc) {
        print_r($obj);
    } else {
        var_dump($obj);
    }
    echo '</pre>';
    if ($exit) exit;
}

function getExceptionMessage($e, $http_code=500) 
{   
    http_response_code($http_code);
    $dev = 1;
    if ($dev==1) {
        return $e->getMessage();
    } else {
        return 'Error has occured.';
    }
}

function currentUser($obj='ID')
{
    /* if (auth()->check()) {
        $user = auth()->user(); 
        return $user[$obj];
    } */
    $result = session('user.'.$obj);
    return $result;
}

function getMenu()
{
    if (empty(session('usermenu'))) {    
        $menumodel = new \App\Models\Menu();
        $menu = $menumodel->getTopNav(currentUser('RoleID'));
        $sessmenu = $menu;
    }
    $sessmenu = request()->session()->get('usermenu');
    return $sessmenu;
    
}

function getSideMenu()
{
    // debug('aaaa');
    // if (empty(session('usermenu'))) {    
        $menumodel = new \App\Models\Menu();
        $menu = $menumodel->getSideNav(currentUser('RoleID'));
        $sessmenu = $menu;
    // }
    // $sessmenu = request()->session()->get('usermenu');
    return $sessmenu;
    
}

function logError($activity, $data, $message){
    try {
        $log = DB::table('log_error')->insert(
            [
                'activity' => $activity,
                'data' => $data,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => currentUser('UserName'),
            ]
        );
    } catch (\Exception $ex) {
        $log = 0;
        // debug($ex->getMessage());
    }
    return $log;
}

function sendSMS($to, $message) {
    $result = new \App\Models\Response();
    $number = $to;
    $masked = substr($number, 0, 4) . str_repeat('*', strlen($number) - 7) . substr($number, -3);
    try {
        $nexmo = new \Nexmo\Client(new \Nexmo\Client\Credentials\Basic('fc03ff7a', '3vtO7EySyLBKzW0p'));
        $sent = $nexmo->message()->send([
            'to' => $to,
            'from' => 'PK2020 BKKBN',
            'text' => $message
        ]);
        $result->status = true;
        $result->message = "OTP berhasil dikirim ke nomor ".$masked;
        $result->data = $sent->getResponseData();
    } catch (\Exception $ex) {
        $result->message = $ex->getMessage();
    }
    return $result->get();
}

function checkAction($obj=null) {
    $menuID = request()->get('mid') ?? 0;
    $menu = auth()->user()->role->menu->where('ID', $menuID)->first();
    $actions = $menu ? $menu->pivot->Actions : '';
    $arr = json_decode($actions, true);
    $result = $arr[$obj]===1 ? true : false;
    return $result;
}

function getReminder() {
    $m = new \App\Models\Helper();
    $reminder = $m->reminder();
    return $reminder;
}

function bundle($package='')
{
    $result = '';
    switch (strtolower($package)) {
        case 'jqgrid':
            $result .= '<link href="'. url('assets/plugins/jqGrid/themes/base/theme.css') .'" rel="stylesheet" type="text/css" />';
            $result .= '<link href="'. url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') .'" rel="stylesheet" type="text/css" />';
            $result .= '<link href="'. url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') .'" rel="stylesheet" type="text/css" />';
            $result .= '<script src="'. url('assets/plugins/jqGrid/jquery.jqgrid.min.js') .'"></script>';
            $result .= '<script src="'. url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') .'"></script>';
            break;
        case 'datatable':
            $result .= '<link href="'. url('assets/plugins/custom/datatables/datatables.bundle.css') .'" rel="stylesheet" type="text/css" />';
            $result .= '<script src="'. url('assets/plugins/custom/datatables/datatables.bundle.js') .'" type="text/javascript"></script>';
        break;
    }
    return $result;
}