<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\User_profile;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class UserProfiles_OrganizationAdminOwn
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
        $id = $request->route('id');

        if (one_from_arr_in_other_arr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
//            dd("here");
            return $next($request);
        }

        if (one_from_arr_in_other_arr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
//            dd("here");
            // Editor may edit UserProfile of a user that belongs to his organization or child organization
            // UserProfile id:
//            dd($id);

            if ($id == '') {
                return $next($request);
            }

            // get department id of editing profile
            $editingDepartmentId = User_profile::whereId($id)->first()->department_id;
//            dd($editingDepartmentId);

            // department id of editor
            $editorDepartmentId = $user->user_profile->department_id;
//            dd($editorDepartmentId);

            // check editingDepartmentId is editorDepartmentId or its child
            $organizations = Organization::all()->toArray();
//            dd($organizations);

            if (isOwn($organizations, $editorDepartmentId, $editingDepartmentId) or $editingDepartmentId === $editorDepartmentId) {
                return $next($request);
            }

            $response = [
                'success' => false,
                'message' => 'Permission to department is absent.'
            ];

            return response()->json($response, 454);
        } else {
//            dd("here");
            // own profile
            $editingUserId = User_profile::whereUserId($user->id)->first()->user_id;
//            dd($editingUserId);
//            dd($id);

            if ($editingUserId == $id) {
                return $next($request);
            }
        }

        $response = [
            'success' => false,
            'message' => 'Permission is absent by the role.'
        ];

        return response()->json($response, 453);
    }
}
