<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Organization;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class Customers_OrganizationUsers
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
        $user = Auth::guard()->user();
        $roles = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();
        $id = $request->route('id');

        if (oneFromArrInOtherArr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }

        if (oneFromArrInOtherArr(['organization-superadmin',
                                  'organization-admin',
                                  'organization-general-manager',
                                  'organization-sales-manager',
                                  'organization-production-manager',
                                  'organization-administrative-leader',
                                  'organization-estimator',
                                  'organization-project-manager',
                                  'organization-administrative-assistant'], $roleNamesArr)) {
            if ($id == '') {
                return $next($request);
            }

            // get department id of editing profile
            $editingDepartment = Customer::whereId($id)->first();

            if (!$editingDepartment) {
                $response = [
                    'success' => false,
                    'message' => "The given data was invalid."
                ];

                return response()->json($response, 422);
            }

            $editingDepartmentId = Customer::whereId($id)->first()->organization_id;

            // department id of editor
            $editorDepartmentId = $user->user_profile->department_id;

            // check editingDepartmentId is editorDepartmentId or its child
            $organizations = Organization::all()->toArray();

            if (isOwn($organizations, $editorDepartmentId, $editingDepartmentId) or $editingDepartmentId === $editorDepartmentId) {
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
