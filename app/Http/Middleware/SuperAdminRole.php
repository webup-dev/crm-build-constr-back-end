<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;

class SuperAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate(); // User $user or false
        $roleName = get_role($user);

        if ($roleName === 'superadmin') {
            return $next($request);
        }

        return redirect('/home');
    }
}
