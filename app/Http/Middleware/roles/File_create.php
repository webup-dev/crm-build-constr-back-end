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

class File_create
{
    use Responses;

    /**
     * Middleware for methods Create of FilesController
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
        $currentUser             = Auth::guard()->user();
        $roleNamesArr            = $currentUser->roles->pluck('name')->all();

        if (oneFromArrInOtherArr([
            'developer',
            'organization-superadmin',
            'organization-admin',
            'organization-general-manager',
            'organization-sales-manager',
            'organization-production-manager',
            'organization-administrative-leader',
            'organization-estimator',
            'organization-project-manager',
            'organization-administrative-assistant',
            'customer-individual',
            'customer-organization'
        ], $roleNamesArr)) {
            return $next($request);
        }

        return response()->json($this->resp(453, 'Middleware.Files'), 453);
    }
}
