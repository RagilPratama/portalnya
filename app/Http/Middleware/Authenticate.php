<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);
        
        if (!$request->ajax() && !in_array($request->path(), ['/','user/profile'])) {
            if (currentUser('RoleID') > 1) {
                $fields = [
                    'NamaLengkap', 
                    'NIK',
                    // 'Alamat',
                    // 'KabupatenKotaID',
                    // 'NoTelepon',
                    // 'Email',
                    // 'NIP',
                    'RoleID',
                    'TingkatWilayahID'
                ];
                foreach ($fields as $field) {
                    if (empty(currentUser($field))) {
                        return redirect('/invalidprofile')->with(compact('field'));
                    }
                }
            }
        }
        return $next($request);
    }
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
