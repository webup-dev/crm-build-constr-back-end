<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreLeadStatus;
use App\Api\V1\Requests\UpdateLeadStatus;
use App\Http\Controllers\Controller;
use App\Models\LeadStatus;
use App\Models\Organization;
use App\Models\User_profile;
use App\Traits\GetOrganizations;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Routing\Helpers;


/**
 * Controller to operate with LeadStatuses
 *
 * @category Controller
 * @package  LeadStatuses
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Lead Statuses
 */
class LeadStatusesController extends Controller
{
    use Helpers;
    use Responses;
    use GetOrganizations;

    /**
     * LeadStatusesController constructor.
     */
    public function __construct()
    {
        $this->middleware('organizations_organization.admin')
            ->only(
                [
                    'index',
                    'show',
                    'store',
                    'getListOfOrganizations',
                    'update',
                    'softDestroy',
                    'indexSoftDeleted',
                    'restore',
                    'destroyPermanently'
                ]
            );
    }

    /**
     * Get index of Lead Statuses
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadStatuses.index. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "parent_id": 3,
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "parent_id": 3,
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
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
     * @response 454 {
     *   "success": false,
     *   "code": 454,
     *   "message":  "Permission to the department is absent.",
     *   "data": null
     * }
     *
     * @return JsonResponse
     */
    public function index()
    {
        $res = $this->_getDepartmentId();
        if ($res === true) {
            $leadStatuses = LeadStatus::with(['organization'])->get();
            if ($leadStatuses->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leadStatuses->toArray();
        } else {
            $organizations = Organization::all()->toArray();
            $collectedIds  = collectIds($organizations, $res);
            $leadStatuses  = LeadStatus::with(['organization'])
                ->whereIn('organization_id', $collectedIds)
                ->get();
            if ($leadStatuses->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leadStatuses->toArray();
        }

        return response()->json(
            $this->resp(
                200,
                'LeadStatuses.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created LeadStatus in DB
     *
     * @param StoreLeadStatus $request Request
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadStatus.store. Result is successful."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "The given data was invalid.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create LeadStatus",
     *      "status_code": 500
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function store(StoreLeadStatus $request)
    {
        $leadStatus = new LeadStatus();

        $leadStatus->name            = $request->get('name');
        $leadStatus->organization_id = $request->get('organization_id');
        $leadStatus->parent_id       = $request->get('parent_id');

        // check permission to organization_id from the request
        $response = $this->_checkPermissionToOrganizationId($leadStatus);
        if ($response !== true) {
            return response()->json($response, 454);
        }

        if ($leadStatus->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadStatuses.store'
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not create LeadStatus',
                500
            );
        }
    }

    /**
     * Check permission to organization_id from the request
     *
     * @param $leadStatus Object
     *
     * @return array|bool
     */
    private function _checkPermissionToOrganizationId($leadStatus)
    {
        $user        = Auth::guard()->user();
        $userProfile = User_profile::whereUserId($user->id)->first();
        if ($userProfile) {
            $userOrganizationId = $userProfile->department_id;
            $organizations      = Organization::all()->toArray();
            $collectedIds       = collectIds($organizations, $userOrganizationId);
            if (!in_array($leadStatus->organization_id, $collectedIds)) {
                return [
                    'success' => false,
                    'message' => 'Permission to the department is absent.'
                ];
            }
        }
        return true;
    }

    /**
     * Show the specified LeadStatus.
     *
     * @param int $id ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "Lead Status 1",
     *       "organization_id": 2,
     *       "parent_id": 3,
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-08 13:25:36",
     *       "organization": "object"
     *     },
     *  "message": "LeadStatuses.show. Result is successful."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $leadStatus = LeadStatus::whereId($id)->first();

        if (!$leadStatus) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadStatuses.show'
                ),
                456
            );
        }

        $leadStatus['organization'] = $leadStatus->organization;

        $data = $leadStatus->toArray();

        return response()->json(
            $this->resp(
                200,
                'LeadStatuses.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified Lead Status.
     *
     * @param UpdateLeadStatus $request Request
     * @param int              $id      ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "LeadStatus Updated",
     *       "organization_id": 2,
     *       "parent_id": 2,
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36",
     *       "organization": "object"
     *     },
     *  "message": "LeadStatuses.update. Result is successful."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "The given data was invalid.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Role",
     *      "status_code": 500
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function update(UpdateLeadStatus $request, $id)
    {
        $leadStatus = LeadStatus::whereId($id)->first();

        if (!$leadStatus) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadStatuses.update'
                ),
                456
            );
        }

        $leadStatus->fill($request->all());

        if ($leadStatus->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadStatuses.update',
                    $leadStatus
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not update Role',
                500
            );
        }
    }

    /**
     * Remove the specified resource from DB.
     *
     * @param int $id ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "LeadStatuses.softDestroy. Result is successful.",
     *  "data": null
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to the department is absent."
     * }
     *
     * @response 500 {
     *  "message": "Could not delete Lead Status."
     * }
     *
     * @return JsonResponse|void
     * @throws \Exception
     */
    public function softDestroy($id)
    {
        $leadStatus = LeadStatus::whereId($id)->first();

        if (!$leadStatus) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadStatuses.softDestroy'
                ),
                456
            );
        }

        if ($leadStatus->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadStatuses.softDestroy'
                ),
                200
            );
        } else {
            return $this->response->error(
                '"Could not delete Lead Status."',
                500
            );
        }
    }

    /**
     * Get index of soft-deleted LeadStatuses
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadStatuses.indexSoftDeleted. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "blogging",
     *    "organization_id": 2,
     *    "parent_id": 2,
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
     *   },
     *   {
     *    "id": 2,
     *    "name": "blogging",
     *    "organization_id": 2,
     *    "parent_id": 2,
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
     *   }]
     * }
     *
     * @response 204 {
     *  "success": true,
     *  "code": 204,
     *  "message": "Empty content.",
     *  "data": null
     * }
     *
     * @response 453 {
     * "success": false,
     * "code": 453,
     * "message":  "Permission is absent by the role.",
     * "data": null
     * }
     *
     * @return JsonResponse
     */
    public function indexSoftDeleted()
    {
        $leadStatus = LeadStatus::with(['organization', 'leadStatusOfParent'])
            ->onlyTrashed()->get();

        if (!$leadStatus->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'LeadStatuses.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'LeadStatuses.indexSoftDeleted',
                $leadStatus
            ), 200
        );
    }

    /**
     * Restore Lead Status
     *
     * @param $id int ID
     *
     * @queryParam id int required User-Details ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadStatuses.restore. Result is successful.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "LeadStatuses.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function restore($id)
    {
        $leadStatus = LeadStatus::onlyTrashed()->whereId($id)->first();

        if (!$leadStatus) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadStatuses.restore'
                ),
                456
            );
        }

        // Restore user-details
        $leadStatus->restore();

        return response()->json(
            $this->resp(200, 'LeadStatuses.restore'),
            200
        );
    }

    /**
     * Destroy Lead Status permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required Lead Status ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "LeadStatuses.destroyPermanently. Result is successful."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function destroyPermanently($id)
    {
        $leadStatus = LeadStatus::withTrashed()->whereId($id)->first();
        if (!$leadStatus) {
            return response()->json(
                $this->resp(456, 'LeadStatuses.destroyPermanently'),
                456
            );
        }

        $leadStatus->forceDelete();

        return response()->json(
            $this->resp(200, 'LeadStatuses.destroyPermanently'),
            200
        );
    }
}
