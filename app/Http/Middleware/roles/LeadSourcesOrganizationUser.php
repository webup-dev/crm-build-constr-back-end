<?php

namespace App\Http\Middleware;

use App\Models\LeadSource;
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
class LeadSourcesOrganizationUser
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
                'platform-admin'
            ], $roleNamesArr
        )
        ) {
            return $next($request);
        } elseif (oneFromArrInOtherArr(
            [
                'organization-superadmin',
                'organization-admin',
                'organization-general-manager',
                'organization-sales-manager',
                'organization-production-manager',
                'organization-administrative-leader',
                'organization-estimator',
                'organization-project-manager',
                'organization-administrative-assistant'
            ],
            $roleNamesArr
        )
        ) {
            $id                     = $request->route('id');
            $lsCategoryDepartmentId = LeadSource::whereId($id)
                ->first()->organization_id;
            $userDepartmentId       = $user->user_profile->department_id;

            if ($lsCategoryDepartmentId === $userDepartmentId) {
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
