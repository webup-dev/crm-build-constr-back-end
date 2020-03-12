<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

/**
 * Middleware to restrict permission except of platform.admin level and higher
 *
 * @category Migration
 * @package  LeadSources
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Migration
 */
class PlatformAdmin
{
    /**
     * Handle an incoming request.
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

        if (one_from_arr_in_other_arr(
            ['developer', 'platform-superadmin', 'platform-admin'],
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
