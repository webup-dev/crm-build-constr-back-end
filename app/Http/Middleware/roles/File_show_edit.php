<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\File;
use App\Models\Organization;
use App\Models\User_profile;
use App\Models\UserCustomer;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;
use App\Traits\Responses;

class File_show_edit
{
    use Responses;

    /**
     * Middleware for methods Update, Show of FilesController
     * Handle an incoming request.
     *
     * Permissions:
     * P1: Platform users
     * O2C: customer's organization users for type "customer"
     * O2U: customer's organization users for type "user"
     * C3C: customer for type "customer"
     * C3U: customer for type "user"
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $user         = Auth::guard()->user();
        $roleNamesArr = $user->roles->pluck('name')->all();
        $id           = $request->route('id');
        $file         = File::whereId($id)->first();

        if (!$file) {
            return response()->json($this->resp(456, 'middleware.Files'), 456);
        }

        $organizations        = Organization::all()->toArray();
        $editorUserDepartment = User_profile::whereUserId($user->id)->first();

        if ($editorUserDepartment) {
            $editorUserDepartmentId = $editorUserDepartment->department_id;
        }

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
            switch ($file->owner_object_type) {
                case 'customer':
                    $customerOrganizationId = Customer::whereId($file->owner_object_id)->first()->organization_id;
                    $access                 = $this->isCustomerYour($customerOrganizationId, $editorUserDepartmentId, $organizations);
                    if ($access) {
                        return $next($request);
                    }

                    return response()->json($this->resp(454, 'middleware.Files'), 454);
                    break;

                case 'user':
                    return response()->json($this->resp(458, 'middleware.Files'), 458);
                    break;
            }
        }

        // current user doesn't have User Department
        if (!$editorUserDepartment) {
            if (one_from_arr_in_other_arr([
                'customer-individual',
                'customer-organization'
            ], $roleNamesArr)) {
                switch ($file->owner_object_type) {
                    case 'customer':
                        $customersIds = UserCustomer::whereUserId($user->id)
                            ->get()
                            ->pluck('customer_id')
                            ->all();

                        if (in_array($file->owner_object_id, $customersIds)) {
                            return $next($request);
                        }

                        return response()->json($this->resp(454, 'Middleware.Files'), 454);
                        break;

                    case 'user':
                        if ($user->id === $file->owner_object_id) {
                            return $next($request);
                        }
                        return response()->json($this->resp(458, 'Middleware.Files'), 458);
                        break;
                }
            }

            return response()->json($this->resp(454, 'Middleware.Files'), 454);
        }
    }

    private function isCustomerYour($customerOrganizationId, $editorUserDepartmentId, $organizations)
    {
        if ($customerOrganizationId != '') {
            if (isOwn($organizations, $editorUserDepartmentId, $customerOrganizationId)) {
                return true;
            }
        }

        return false;
    }
}
