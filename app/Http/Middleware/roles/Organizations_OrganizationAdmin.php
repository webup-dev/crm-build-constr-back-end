<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

/**
 * Middleware to operate permissions to organizations
 *
 * @category Middleware
 * @package  Organizations
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Middleware
 */
class Organizations_OrganizationAdmin
{
    /**
     * Middleware for routes with organization ID in URL.
     * Handle an incoming request.
     *     'developer', 'platform-superadmin', 'platform-admin' has permissions
     *     always
     *     'organization-superadmin', 'organization-admin' has permissions
     *     to own organizations only
     *     other roles don't have permissions
     *
     * @param \Illuminate\Http\Request $request Request
     * @param \Closure                 $next    Next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard()->user();

        $roles = $user->roles;

        $roleNamesArr = $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(
            ['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr
        )
        ) {
            return $next($request);
        }

        if (oneFromArrInOtherArr(
            ['organization-superadmin', 'organization-admin'], $roleNamesArr
        )
        ) {
            $id = $request->route('id');

            $departmentId = $user->user_profile->department_id;

            if ($id == '') {
                $request->request->add(['parent_id' => $departmentId]);
                return $next($request);
            }

            $organizations = Organization::withTrashed()
                ->get()
                ->toArray();

            if (isOwn($organizations, $departmentId, $id)) {
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
