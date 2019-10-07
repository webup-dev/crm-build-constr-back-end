<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class PlatformSuperadmin
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
        $user = Auth::guard()->user();

        $roles = $user->roles;

        $roleNamesArr= $roles->pluck('name')->all();

        if (one_from_arr_in_other_arr(['developer', 'superadmin', 'platform-superadmin'], $roleNamesArr)) {
            return $next($request);
        }

        $response = [
            'success' => false,
            'message' => 'You do not have permissions.'
        ];

        return response()->json($response, 401);
    }
}
