<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreLeadType;
use App\Api\V1\Requests\UpdateLeadType;
use App\Http\Controllers\Controller;
use App\Models\LeadType;
use App\Models\Organization;
use App\Models\User_profile;
use App\Traits\GetOrganizations;
use App\Api\V1\Traits\CheckPermissionToOrganizationId;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Routing\Helpers;


/**
 * Controller to operate with LeadTypes
 *
 * @category Controller
 * @package  LeadTypes
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Lead Types
 */
class LeadTypesController extends Controller
{
    use Helpers;
    use Responses;
    use CheckPermissionToOrganizationId;
    use GetOrganizations;

    /**
     * LeadTypesController constructor.
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
     * Get index of Lead Types
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadTypes.index. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Website - CertainFeed",
     *    "organization_id": 2,
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
     *   "message":  "Permission to the department is absent .",
     *   "data": null
     * }
     *
     * @return JsonResponse
     */
    public function index()
    {
        $res = $this->_getDepartmentId();
        if ($res === true) {
            $leadTypes = LeadType::with(['organization'])->get();
            if ($leadTypes->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leadTypes->toArray();
        } else {
            $organizations = Organization::all()->toArray();
            $collectedIds  = collectIds($organizations, $res);
            $leadTypes   = LeadType::with(['organization'])
                ->whereIn('organization_id', $collectedIds)
                ->get();
            if ($leadTypes->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leadTypes->toArray();
        }

        return response()->json(
            $this->resp(
                200,
                'LeadTypes.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created LeadType in DB
     *
     * @param StoreLeadType $request Request
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadType.store. Result is successful."
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
     *      "message": "Could not create LeadType",
     *      "status_code": 500
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function store(StoreLeadType $request)
    {
        $leadType = new LeadType();

        $leadType->name            = $request->get('name');
        $leadType->organization_id = $request->get('organization_id');

        // check permission to organization_id from the request
        $response = $this->_checkPermissionToOrganizationId($leadType);
        if ($response !== true) {
            return response()->json($response, 454);
        }

        if ($leadType->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadTypes.store'
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not create LeadType',
                500
            );
        }
    }

    /**
     * Check permission to organization_id from the request
     *
     * @param $leadType Object
     *
     * @return array|bool
     */
    private function _checkPermissionToOrganizationId($leadType)
    {
        $user        = Auth::guard()->user();
        $userProfile = User_profile::whereUserId($user->id)->first();
        if ($userProfile) {
            $userOrganizationId = $userProfile->department_id;
            $organizations      = Organization::all()->toArray();
            $collectedIds       = collectIds($organizations, $userOrganizationId);
            if (!in_array($leadType->organization_id, $collectedIds)) {
                return [
                    'success' => false,
                    'message' => 'Permission to the department is absent.'
                ];
            }
        }
        return true;
    }

    /**
     * Show the specified Role.
     *
     * @param int $id ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "Lead Type 1",
     *       "organization_id": 2,
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-08 13:25:36",
     *       "organization": "object"
     *     },
     *  "message": "LeadTypes.show. Result is successful."
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
        $leadType = LeadType::whereId($id)->first();

        if (!$leadType) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadTypes.show'
                ),
                456
            );
        }

        $leadType['organization'] = $leadType->organization;

        $data = $leadType->toArray();

        return response()->json(
            $this->resp(
                200,
                'LeadTypes.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified Lead Type.
     *
     * @param UpdateLeadType $request Request
     * @param int            $id      ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "LeadType Updated",
     *       "organization_id": 2,
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36"
     *     },
     *  "message": "LeadTypes.update. Result is successful."
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
    public function update(UpdateLeadType $request, $id)
    {
        $leadType = LeadType::whereId($id)->first();

        if (!$leadType) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadTypes.update'
                ),
                456
            );
        }

        $leadType->fill($request->all());

        if ($leadType->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadTypes.update',
                    $leadType
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
     * Remove the specified resource from storage.
     *
     * @param int $id ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "LeadTypes.softDestroy. Result is successful.",
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
     *  "message": "Could not delete Lead Type."
     * }
     *
     * @return JsonResponse|void
     * @throws \Exception
     */
    public function softDestroy($id)
    {
        $leadType = LeadType::whereId($id)->first();

        if (!$leadType) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadTypes.softDestroy'
                ),
                456
            );
        }

        if ($leadType->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadTypes.softDestroy'
                ),
                200
            );
        } else {
            return $this->response->error(
                '"Could not delete Lead Type."',
                500
            );
        }
    }

    /**
     * Get index of soft-deleted LeadTypes
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadTypes.indexSoftDeleted. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "blogging",
     *    "organization_id": 2,
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Social Media",
     *    "organization_id": 2,
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
        $leadTypes = LeadType::with(['organization'])
            ->onlyTrashed()->get();

        if (!$leadTypes->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'LeadTypes.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'LeadTypes.indexSoftDeleted',
                $leadTypes
            ), 200
        );
    }

    /**
     * Restore Lead Type
     *
     * @param $id int ID
     *
     * @queryParam id int required User-Details ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadTypes.restore. Result is successful.",
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
     *  "message": "LeadTypes.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function restore($id)
    {
        $leadType = LeadType::onlyTrashed()->whereId($id)->first();

        if (!$leadType) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadTypes.restore'
                ),
                456
            );
        }

        // Restore user-details
        $leadType->restore();

        return response()->json(
            $this->resp(200, 'LeadTypes.restore'),
            200
        );
    }

    /**
     * Destroy Lead Type permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required Lead Type ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "LeadTypes.destroyPermanently. Result is successful."
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
        $leadType = LeadType::withTrashed()->whereId($id)->first();
        if (!$leadType) {
            return response()->json(
                $this->resp(456, 'LeadTypes.destroyPermanently'),
                456
            );
        }

        $leadType->forceDelete();

        return response()->json(
            $this->resp(200, 'LeadTypes.destroyPermanently'),
            200
        );
    }
}
