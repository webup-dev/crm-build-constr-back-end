<?php

namespace App\Traits;

use App\Models\Organization;
use App\Models\User_profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

/**
 * Trait to get organizations
 *
 * @category Trait
 * @package  Controller
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Trait
 */
trait GetOrganizations
{
    /**
     * Get organizations that are dependent on a user role
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadTypes.index. Result is successful.",
     *  "data": [{
     *    "id": 2,
     *    "level": 1,
     *    "order": 1,
     *    "name": "WNY",
     *    "parent_id": null,
     *    "deleted_a": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 9,
     *    "level": 1,
     *    "order": 2,
     *    "name": "Spring",
     *    "parent_id": null,
     *    "deleted_a": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }]
     * }
     *
     * @response 204 {
     *  "message": "No content"
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Permission is absent due to Role.",
     *   "data": null
     * }
     *
     * @return JsonResponse
     */
    public function getListOfOrganizations()
    {
        $res = $this->_getDepartmentId();
        if ($res === true) {
            $organizations = Organization::whereLevel(1)->get();
            if ($organizations->count() === 0) {
                return response()->json('', 204);
            }

            $data = $organizations->toArray();
        } else {
            $data = Organization::whereIn('id', [$res])->get()->toArray();
        }

        return response()->json(
            $this->resp(
                200,
                'Trait.getListOfOrganizations',
                $data
            ),
            200
        );
    }

    /**
     * Get Department Id
     *
     * @return mixed true|department ID if platform level|organizational user
     */
    private function _getDepartmentId()
    {
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(
            [
                'developer',
                'platform-superadmin',
                'platform-admin'
            ], $roleNamesArr
        )
        ) {
            return true;
        }

        return User_profile::whereUserId($user->id)->first()->department_id;
    }
}
