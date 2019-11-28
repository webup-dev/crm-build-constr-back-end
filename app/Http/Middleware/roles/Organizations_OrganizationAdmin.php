<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class Organizations_OrganizationAdmin
{
    /**
     * Middleware for routes with organization ID in URL.
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
//        $id = $request->route('id');
//        if ($id == 2) {
//            dd('middleware');
//        }
//        dd($request);
        $user = Auth::guard()->user();

        $roles = $user->roles;

        $roleNamesArr= $roles->pluck('name')->all();

        if (one_from_arr_in_other_arr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }

        if (one_from_arr_in_other_arr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
            $id = $request->route('id');

            $departmentId = $user->user_profile->department_id;

            if ($id == '') {
                $request->request->add(['parent_id' => $departmentId]);
                return $next($request);
            }

            $organizations = Organization::all()->toArray();
            if (isOwn($organizations, $departmentId, $id)) {
                return $next($request);
            }

            $response = [
                'success' => false,
                'message' => 'Permission to department is absent.'
            ];

            return response()->json($response, 454);
        } else {
            // own account

        }

        $response = [
            'success' => false,
            'message' => 'Permission is absent by the role.'
        ];

        return response()->json($response, 453);
    }
}
