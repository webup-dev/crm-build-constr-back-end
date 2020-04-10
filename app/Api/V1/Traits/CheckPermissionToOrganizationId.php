<?php

namespace App\Api\V1\Traits;

use App\Models\Organization;
use App\Models\User_profile;
use Illuminate\Support\Facades\Auth;

/**
 * Trait to check Permission To Organization Id
 *
 * @category Trait
 * @package  Controller
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Trait
 */
trait CheckPermissionToOrganizationId
{
    /**
     * Check permission to organization_id from the request
     *
     * @param $item Object
     *
     * @return array|bool
     */
    private function _checkPermissionToOrganizationId($item)
    {
        $user        = Auth::guard()->user();
        $userProfile = User_profile::whereUserId($user->id)->first();
        if ($userProfile) {
            $userOrganizationId = $userProfile->department_id;
            $organizations      = Organization::all()->toArray();
            $collectedIds       = collectIds($organizations, $userOrganizationId);
            if (!in_array($item->organization_id, $collectedIds)) {
                return [
                    'success' => false,
                    'message' => 'Permission to the department is absent.'
                ];
            }
        }
        return true;
    }
}
