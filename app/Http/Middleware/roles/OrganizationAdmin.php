<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Auth;

/**
 * Middleware to give access for Organization Admin and higher
 *
 * @category Middleware
 * @package  WNY2
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Middleware
 */
class OrganizationAdmin
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
            $id           = $request->route('id');
            $departmentId = $user->user_profile->department_id;

            if ($id == '') {
                $request->request->add(['parent_id' => $departmentId]);
                return $next($request);
            }

            if ($id === $departmentId) {
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
