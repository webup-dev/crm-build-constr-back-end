<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreStageRequest;
use App\Api\V1\Requests\UpdateStage;
use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Organization;
use App\Api\V1\Traits\CheckPermissionToOrganizationId;
use App\Traits\Responses;
use App\Traits\GetOrganizations;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Routing\Helpers;


/**
 * Controller to operate with Stages
 *
 * @category Controller
 * @package  Stages
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Stages
 */
class StagesController extends Controller
{
    use Helpers;
    use Responses;
    use CheckPermissionToOrganizationId;
    use GetOrganizations;

    /**
     * StagesController constructor.
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
     * Get index of Stages
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Stages.index. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
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
     * @return JsonResponse
     */
    public function index()
    {
        $res = $this->_getDepartmentId();
        if ($res === true) {
            $stages = Stage::with(['organization'])->get();
            if ($stages->count() === 0) {
                return response()->json('', 204);
            }

            $data = $stages->toArray();
        } else {
            $organizations = Organization::all()->toArray();
            $collectedIds  = collectIds($organizations, $res);
            $stages        = Stage::with(['organization'])
                ->whereIn('organization_id', $collectedIds)
                ->get();
            if ($stages->count() === 0) {
                return response()->json('', 204);
            }

            $data = $stages->toArray();
        }

        return response()->json(
            $this->resp(
                200,
                'Stages.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created Stage in DB
     *
     * @param StoreStageRequest $request Request
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Stage.store. Result is successful."
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
     *      "message": "Could not create Stage",
     *      "status_code": 500
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function store(StoreStageRequest $request)
    {
        $stage = new Stage();

        $stage->name            = $request->get('name');
        $stage->organization_id = $request->get('organization_id');
        $stage->workflow_type   = $request->get('workflow_type');
        $stage->description     = $request->get('description');

        // check permission to organization_id from the request
        $response = $this->_checkPermissionToOrganizationId($stage);
        if ($response !== true) {
            return response()->json($response, 454);
        }

        if ($stage->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'Stages.store'
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not create Stage',
                500
            );
        }
    }

    /**
     * Show the specified Stage.
     *
     * @param int $id ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "Lead Status 1",
     *       "organization_id": 2,
     *       "workflow_type": "request",
     *       "description": "",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-08 13:25:36",
     *       "organization": "object"
     *     },
     *  "message": "Stages.show. Result is successful."
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
     * @return JsonResponse
     */
    public function show($id)
    {
        $stage = Stage::whereId($id)->first();

        if (!$stage) {
            return response()->json(
                $this->resp(
                    456,
                    'Stages.show'
                ),
                456
            );
        }

        $stage['organization'] = $stage->organization;

        $data = $stage->toArray();

        return response()->json(
            $this->resp(
                200,
                'Stages.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified Stage.
     *
     * @param UpdateStage $request Request
     * @param int         $id      ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "Stage Updated",
     *       "organization_id": 2,
     *       "workflow_type": "request",
     *       "description": "",
     *       "deleted_at": null,
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36",
     *       "organization": "object"
     *     },
     *  "message": "Stages.update. Result is successful."
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
    public function update(UpdateStage $request, $id)
    {
        $stage = Stage::whereId($id)->first();

        if (!$stage) {
            return response()->json(
                $this->resp(
                    456,
                    'Stages.update'
                ),
                456
            );
        }

        $stage->fill($request->all());

        if ($stage->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'Stages.update',
                    $stage
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
     *  "message": "Stages.softDestroy. Result is successful.",
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
        $stage = Stage::whereId($id)->first();

        if (!$stage) {
            return response()->json(
                $this->resp(
                    456,
                    'Stages.softDestroy'
                ),
                456
            );
        }

        if ($stage->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'Stages.softDestroy'
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
     * Get index of soft-deleted Stages
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Stages.indexSoftDeleted. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "blogging",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object"
     *   },
     *   {
     *    "id": 2,
     *    "name": "blogging",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
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
        $stage = Stage::with(['organization'])
            ->onlyTrashed()->get();

        if (!$stage->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'Stages.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'Stages.indexSoftDeleted',
                $stage
            ), 200
        );
    }

    /**
     * Restore Stage
     *
     * @param $id int ID
     *
     * @queryParam id int required Stage ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Stages.restore. Result is successful.",
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
     *  "message": "Stages.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function restore($id)
    {
        $stage = Stage::onlyTrashed()->whereId($id)->first();

        if (!$stage) {
            return response()->json(
                $this->resp(
                    456,
                    'Stages.restore'
                ),
                456
            );
        }

        // Restore user-details
        $stage->restore();

        return response()->json(
            $this->resp(200, 'Stages.restore'),
            200
        );
    }

    /**
     * Destroy Stage permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required Stage ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Stages.destroyPermanently. Result is successful."
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
        $stage = Stage::withTrashed()->whereId($id)->first();
        if (!$stage) {
            return response()->json(
                $this->resp(456, 'Stages.destroyPermanently'),
                456
            );
        }

        $stage->forceDelete();

        return response()->json(
            $this->resp(200, 'Stages.destroyPermanently'),
            200
        );
    }
}
