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

class File_index
{
    use Responses;

    /**
     * Middleware for method Index of FilesController
     * Handle an incoming request.
     *
     * URL: customers/{customerId}/files
     *
     * Permissions:
     * P. Platform users: to all files
     * OC. customer's organization users for type "customer": to all files of own customer
     * OU. customer's organization users for type "user": there is not permissions
     * CC. user-customer for type "customer": to all files of own customer
     * CU. user-customer for type "user": to own files only
     * G: guest: there is not permissions
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $currentUser   = Auth::guard()->user();
        $roleNamesArr  = $currentUser->roles->pluck('name')->all();
        $urlCustomerId = $request->route('id');

        if (one_from_arr_in_other_arr([
            'developer',
            'platform-superadmin',
            'platform-admin'
        ], $roleNamesArr)) {
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
            // check is the customer your?
            /**
             * get all own customers -> get their department ids
             * check is your department among customer departments?
             */
            $departmentId = User_profile::whereUserId($currentUser->id)
                ->first()
                ->department_id;

            $customerIds = Customer::whereOrganizationId($departmentId)
                ->get()
                ->pluck('id')
                ->toArray();

            if (in_array($urlCustomerId, $customerIds)) {
                return $next($request);
            }

            return response()->json($this->resp(454, 'Middleware.Files'), 454);
        }

        if (one_from_arr_in_other_arr([
            'customer-individual',
            'customer-organization'
        ], $roleNamesArr)) {
            // check is the customer your?
            /**
             * Get all user's customers (ids)
             * Check is url's customer among user's customers?
             */

            $customerIds = UserCustomer::whereUserId($currentUser->id)
                ->get()
                ->pluck('id')
                ->toArray();

            if (in_array($urlCustomerId, $customerIds)) {
                return $next($request);
            }

            return response()->json($this->resp(458, 'Middleware.Files'), 458);
        }

        return response()->json($this->resp(453, 'Middleware.Files'), 453);
    }
}
