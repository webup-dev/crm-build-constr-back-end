<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

/**
 * Middleware OrganizationUser
 *
 * @category Middleware
 * @package  App\Http\Middleware
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Middleware
 */
class OrganizationUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request Request
     * @param \Closure $next    Next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard()->user();
        $roles = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(
            [
                'developer',
                'platform-superadmin',
                'platform-admin',
                'organization-superadmin',
                'organization-admin',
                'organization-general-manager',
                'organization-sales-manager',
                'organization-production-manager',
                'organization-administrative-leader',
                'organization-estimator',
                'organization-project-manager',
                'organization-administrative-assistant'
            ], $roleNamesArr
        )
        ) {
            return $next($request);
        }

        $response = [
            'success' => false,
            'message' => 'Permission is absent by the role.'
        ];

        return response()->json($response, 453);
    }
}
