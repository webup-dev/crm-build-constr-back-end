<?php

namespace App\Http\Middleware;

use App\Models\LeadSource;
use App\Models\LsCategory;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Auth;

/**
 * Middleware to give access to Lead Sources for Organization Admin and higher
 * any ID is not included
 *
 * @category Middleware
 * @package  WNY2
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Middleware
 */
class LeadSourcesOrganizationAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request Request
     * @param \Closure $next    Closure Next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(
            [
                'developer', 'platform-superadmin', 'platform-admin'
            ],
            $roleNamesArr
        )
        ) {
            return $next($request);
        }

        if (oneFromArrInOtherArr(
            [
                'organization-superadmin', 'organization-admin'
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
