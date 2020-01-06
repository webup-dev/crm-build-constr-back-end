<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\User_profile;
use App\Models\UserCustomer;
use App\Models\UserDetail;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class Store_Show_Update__user_details
{
    /**
     * Middleware for methods Store, Show, Update of UserDetailsController
     * Handle an incoming request.
     * User is being selected through user_customers, (->) customers, (->) organization
     *
     * Permissions:
     * P1: Platform users
     * P2: customer's organization users
     * P3: customer for own users
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $user                 = Auth::guard()->user();
        $roles                = $user->roles;
        $roleNamesArr         = $roles->pluck('name')->all();
        $id                   = $request->route('id');
        $editorUserDepartment = User_profile::whereUserId($user->id)->first();

        // check editingDepartmentId is editorDepartmentId or its child
        $organizations = Organization::all()->toArray();

        if (!$editorUserDepartment) {

            if (one_from_arr_in_other_arr([
                'customer-individual',
                'customer-organization'
            ], $roleNamesArr)) {
                $access = $this->customer($user, $id, $request);

                if ($access) {
                    return $next($request);
                }

                $response = [
                    'success' => false,
                    'code'    => 454,
                    'message' => 'Permission to the department is absent.',
                    'data'    => null
                ];

                return response()->json($response, 454);
            }

            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

        $editorUserDepartmentId = $editorUserDepartment->department_id;

        // P1 (see phpDoc)
        if (one_from_arr_in_other_arr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }
        if (one_from_arr_in_other_arr([
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
            $access = $this->organizationalUser($user, $id, $editorUserDepartmentId, $organizations, $request, $next);
            if ($access) {
                return $next($request);
            }

            $response = [
                'success' => false,
                'code'    => 454,
                'message' => 'Permission to the department is absent.',
                'data'    => null
            ];

            return response()->json($response, 454);
        }

        $response = [
            'success' => false,
            'message' => 'Permission is absent by the role.'
        ];

        return response()->json($response, 453);
    }

    private function organizationalUser($user, $id, $editorUserDepartmentId, $organizations, $request, $next)
    {
        // check the access to the department of the customer
        // store. P2 (see phpDoc)
        if ($id == '') {
            $userId          = $request->user_id;
            $customerIds     = UserCustomer::whereUserId($userId)
                ->get()
                ->pluck('customer_id')
                ->all();
            $organizationIds = Customer::whereIn('id', $customerIds)
                ->get()
                ->pluck('organization_id')
                ->all();

            foreach ($organizationIds as $orgId) {
                if (isOwn($organizations, $editorUserDepartmentId, $orgId)) {
                    return true;
                }
            }

            return false;
        }

        // store,update. P2 (see phpDoc)
        // get department id of editing userDetail
        $editingUserId   = UserDetail::whereId($id)
            ->first()
            ->user_id;
        $customerIds     = UserCustomer::whereUserId($editingUserId)
            ->get()
            ->pluck('customer_id')
            ->all();
        $organizationIds = Customer::whereIn('id', $customerIds)
            ->get()
            ->pluck('organization_id')
            ->all();

        foreach ($organizationIds as $orgId) {
            if (isOwn($organizations, $editorUserDepartmentId, $orgId)) {
                return true;
            }
        }

        return false;
    }

    private function customer($user, $id, $request)
    {
        $editorCustomerIds = UserCustomer::whereUserId($user->id)
            ->get()
            ->pluck('customer_id')
            ->all();

        // store. P3 (see phpDoc)
        if ($id == '') {
            $userId      = $request->user_id;
            $customerIds = UserCustomer::whereUserId($userId)
                ->get()
                ->pluck('customer_id')
                ->all();


            $intersect = array_intersect($editorCustomerIds, $customerIds);

            if (count($intersect) === 0) {
                return false;
            }

            return true;
        }

        // store,update. P3 (see phpDoc)
        // get customer ids of editing userDetail
        $editingUserId = UserDetail::whereId($id)
            ->first()
            ->user_id;
        $customerIds   = UserCustomer::whereUserId($editingUserId)
            ->get()
            ->pluck('customer_id')
            ->all();

        $intersect = array_intersect($editorCustomerIds, $customerIds);

        if (count($intersect) === 0) {
            return false;
        }

        return true;
    }
}
