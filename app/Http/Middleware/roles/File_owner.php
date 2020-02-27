<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\File;
use App\Models\Organization;
use Closure;
use Auth;
use App\Traits\Responses;

class File_owner
{
    use Responses;

    /**
     * Middleware for methods SoftDelete of FilesController
     * Handle an incoming request.
     *
     * Permission to own file only
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $currentUser  = Auth::guard()->user();
        $roles        = $currentUser->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        // check platform's permissions
        if (one_from_arr_in_other_arr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $next($request);
        }

        $idFromUrl = $request->route('id');
        $file      = File::whereId($idFromUrl)->first();

        if (!$file) {
            return response()->json($this->resp(456, 'middleware.Files'), 456);
        }

        // check owner
        if ($currentUser->id === $file->owner_user_id) {
            return $next($request);
        }

        // check organization admin's permission
        if (one_from_arr_in_other_arr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
            // Check is it customer's file:
            if ($file->owner_object_type !== 'customer') {
                return response()->json($this->resp(454, 'middleware.Files'), 454);
            }

            // get organization_id of the customer

            $customerId          = $file->owner_object_id;
            $editingDepartmentId = Customer::whereId($customerId)->first()->organization_id;

            // department id of editor
            $editorDepartmentId = $currentUser->user_profile->department_id;

            // check editingDepartmentId is editorDepartmentId or its child
            $organizations = Organization::all()->toArray();

            if (isOwn($organizations, $editorDepartmentId, $editingDepartmentId)) {
                return $next($request);
            }

            return response()->json($this->resp(454, 'middleware.Files'), 454);
        }

        return response()->json($this->resp(457, 'middleware.Files'), 457);
    }
}
