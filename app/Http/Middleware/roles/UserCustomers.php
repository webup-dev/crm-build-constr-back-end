<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\UserCustomer;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class UserCustomers
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
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();
        $id           = $request->route('id');

        if (oneFromArrInOtherArr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }

        if (oneFromArrInOtherArr([
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
            if ($id == '') {
                return $next($request);
            }

            // get department id of the user-customer
            $userCustomer = UserCustomer::whereId($id)->first();

            if (!$userCustomer) {
                $response = [
                    'success' => false,
                    'code'    => 456,
                    'message' => "Incorrect entity ID.",
                    'data'    => null
                ];

                return response()->json($response, 456);
            }

            $customerId = $userCustomer->customer_id;
            $customer   = Customer::whereId($customerId)->first();

            if (!$customer) {
                $response = [
                    'success' => false,
                    'message' => "The given data was invalid."
                ];

                return response()->json($response, 422);
            }

            $organizationId = $customer->organization_id;

            // department id of editor
            $editorDepartmentId = $user->user_profile->department_id;

            // check editingDepartmentId is editorDepartmentId or its child
            $organizations = Organization::all()->toArray();

            if (isOwn($organizations, $editorDepartmentId, $organizationId) or $organizationId === $editorDepartmentId) {
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
