<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\User_profile;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class UserProfiles_OrganizationAdmin
{
    /**
     * Middleware for routes with UserProfile ID in URL.
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
//        dd($request);
        $user = Auth::guard()->user();

        $roles = $user->roles;

        $roleNamesArr = $roles->pluck('name')->all();
//        dd($roleNamesArr);

        if (one_from_arr_in_other_arr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }

        if (one_from_arr_in_other_arr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
//            dd("here middlware");
            // Editor may edit UserProfile of a user that belongs to his organization or child organization
            // UserProfile id:
            $id = $request->route('id');
//            dd($id);

            if ($id == '') {
                return $next($request);
            }

            // get department id of editing profile
            $editingDepartmentId = User_profile::whereId($id)->first()->department_id;
//            dd($editingDepartmentId);

            // department id of editor
            $editorDepartmentId = $user->user_profile->department_id;
//            dd($editingDepartmentId);

            // check editingDepartmentId is editorDepartmentId or its child
            $organizations = Organization::all()->toArray();
//            dd($organizations);

            if (isOwn($organizations, $editorDepartmentId, $editingDepartmentId)) {
//                dd("permitted");
                return $next($request);
            }
//            dd("not permitted");

            $response = [
                'success' => false,
                'message' => 'Permission to department is absent.'
            ];

            return response()->json($response, 454);
        }

        $response = [
            'success' => false,
            'message' => 'Permission is absent by the role.'
        ];

        return response()->json($response, 453);
    }
}
