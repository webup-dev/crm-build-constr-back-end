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

class Index_softdestroy__user_details
{
    /**
     * Middleware for methods index, softDestroy, destroyPermanently of UserDetailsController
     * Handle an incoming request.
     * User is being selected through user_customers, (->) customers, (->) organization
     *
     * Permissions:
     * P1: Platform users
     * P2: customer's organization admin level
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $user              = Auth::guard()->user();
        $roles             = $user->roles;
        $roleNamesArr      = $roles->pluck('name')->all();
        $id                = $request->route('id');
        $editorUserProfile = User_profile::whereUserId($user->id)->first();

        if (!$editorUserProfile) {
            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

        $editorUserDepartmentId = $editorUserProfile->department_id;

        // check editingDepartmentId is editorDepartmentId or its child
        $organizations = Organization::all()->toArray();

        // P1 (see phpDoc)
        if (one_from_arr_in_other_arr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }

        // P2 (see phpDoc)
        if (one_from_arr_in_other_arr([
            'organization-superadmin',
            'organization-admin'
        ], $roleNamesArr)) {
            if ($this->organizationalAdmin($user, $id, $editorUserDepartmentId, $organizations, $request, $next)) {
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

    private function organizationalAdmin($user, $id, $editorUserDepartmentId, $organizations, $request, $next)
    {
        // check the access to the department of the customer
        // index. P2 (see phpDoc)
        if ($id === null) {
            return $next($request);
        }

        // softDelete. P2 (see phpDoc)
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
}
