<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Organization;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class Customers_OrganizationSuperadmin
{
    /**
     * Middleware for routes with Customers ID in URL.
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

        $roleNamesArr = $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }

        if (oneFromArrInOtherArr(['organization-superadmin'], $roleNamesArr)) {
            // Editor may edit a Customer that belongs to his organization or child organization
            // Customer id:
            $id = $request->route('id');

            // If customer ID is absent in the URL
            if ($id == '') {
                return $next($request);
            }

            // get organization_id of the customer
            $editingDepartmentId = Customer::whereId($id)->first()->organization_id;

            // department id of editor
            $editorDepartmentId = $user->user_profile->department_id;

            // check editingDepartmentId is editorDepartmentId or its child
            $organizations = Organization::all()->toArray();

            if (isOwn($organizations, $editorDepartmentId, $editingDepartmentId)) {
                return $next($request);
            }

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
