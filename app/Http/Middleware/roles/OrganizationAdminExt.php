<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class OrganizationAdminExt
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
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        $access = false;
        if (one_from_arr_in_other_arr(['developer', 'superadmin', 'platform-superadmin', 'organization-superadmin', 'organization-admin'], $roleNamesArr)) {
//
            $access = true;
        }

        if (!$access) {
            $userProfile = $user->user_profile;
            $id          = $request->route()->parameter('id');
            if ($id == $userProfile->user_id) {
                $access = true;
            }
        }

        if ($access) {
            return $next($request);
        } else {
            $response = [
                'success' => false,
                'message' => 'You do not have permissions.'
            ];

            return response()->json($response, 453);
        }
    }
}
