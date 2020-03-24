<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\User_profile;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class Customers_OrganizationAdminOwn
{
    /**
     * Middleware for routes with Customers ID in URL.
     * The same as Customers_OrganizationAdmin but with permission for the Customer.
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

        if (oneFromArrInOtherArr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
//            dd("here");
            return $next($request);
        }

        if (oneFromArrInOtherArr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
//            dd("here");
            // Editor may edit UserProfile of a user that belongs to his organization or child organization
            // UserProfile id:
//            dd($id);

            if ($id == '') {
                return $next($request);
            }

            // get department id of editing profile
            $editingDepartmentId = Customer::whereId($id)->first()->organization_id;
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
            // check is current user a customer
            $editingUserId = Customer::whereId($id)->first()->user_id;
//            dd($editingUserId);
//            dd($id);

            if ($editingUserId == $user->id) {
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
