<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\User_profile;
use Closure;
use Auth;

/**
 * Middleware to operate permissions to user profiles
 *
 * @category Middleware
 * @package  User_Profiles
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Middleware
 */
class UserProfiles_OrganizationAdmin
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
            // Editor may edit UserProfile of a user that belongs
            // to his organization or child organization
            // UserProfile id:
            $id = $request->route('id');

            if ($id == '') {
                return $next($request);
            }

            // get department id of editing profile
            $editingDepartmentId = User_profile::whereId($id)
                ->first()->department_id;

            // department id of editor
            $editorDepartmentId = $user->user_profile->department_id;

            // check editingDepartmentId is editorDepartmentId or its child
            $organizations = Organization::all()->toArray();

            if (isOwn($organizations, $editorDepartmentId, $editingDepartmentId)) {
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
