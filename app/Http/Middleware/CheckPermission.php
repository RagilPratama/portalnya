<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission=null)
    {
        if (auth()->check()) {
        $role = auth()->user()->role;
        $userPermissions = $role->menu->where('Permission','!=',NULL)->pluck('Permission')->toArray();
        $vpermission = explode(',', $permission);
        $check = array_intersect($vpermission, $userPermissions);
        if (empty($check)) {
            if ($request->ajax()){
                return response()->json('Forbidden', 403);
            } else {
                abort(403);
            }
        }
        return $next($request);
        } else {
            if ($request->ajax()){
                return response()->json('Unauthorized', 401);
            } else {
                return redirect('/login');
            }
        }
    }
}
