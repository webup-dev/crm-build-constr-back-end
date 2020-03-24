<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Auth;

/**
 * Middleware to give access for Organization Superadmin and higher
 *
 * @category Middleware
 * @package  WNY2
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Middleware
 */
class OrganizationSuperadmin
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
        $user = Auth::guard()->user();

        $roles = $user->roles;

        $roleNamesArr= $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(
            [
                'developer',
                'platform-superadmin',
                'platform-admin',
                'organization-superadmin',
                'organization-admin'
            ],
            $roleNamesArr
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
