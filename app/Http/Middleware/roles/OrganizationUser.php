<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class OrganizationUser
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

        if (one_from_arr_in_other_arr(
            [
                'developer',
                'platform-superadmin',
                'platform-admin',
                'organization-superadmin',
                'organization-admin',
                'organization-general-manager',
                'organization-sales-manager',
                'organization-production-manager',
                'organization-administrative-leader',
                'organization-estimator',
                'organization-project-manager',
                'organization-administrative-assistant'
            ], $roleNamesArr)) {
            return $next($request);
        }

        $response = [
            'success' => false,
            'message' => 'Permission is absent by the role.'
        ];

        return response()->json($response, 453);
    }
}
