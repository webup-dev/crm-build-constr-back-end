<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreStageRequest;
use App\Api\V1\Requests\StoreWorkflowRequest;
use App\Api\V1\Requests\UpdateStage;
use App\Api\V1\Requests\UpdateWorkflow;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Api\V1\Traits\CheckPermissionToOrganizationId;
use App\Models\Workflow;
use App\Traits\Responses;
use App\Traits\GetOrganizations;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Routing\Helpers;

/**
 * Controller to operate with Workflows
 *
 * @category Controller
 * @package  Workflows
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Workflows
 */
class WorkflowsController extends Controller
{
    use Helpers;
    use Responses;
    use CheckPermissionToOrganizationId;
    use GetOrganizations;

    /**
     * WorkflowsController constructor.
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
     *    "organization": "object",
     *    "stages": [{
     *        "id": 1,
     *        "organization_id": 2,
     *        "name": "Documenting",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 1
     *          }
     *      },
     *      {
     *        "id": 2,
     *        "organization_id": 2,
     *        "name": "Evaluation",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 2
     *          }
     *      }
     *    ]
     *   },
     *   {
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object",
     *    "stages": [{
     *        "id": 1,
     *        "organization_id": 2,
     *        "name": "Documenting",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 1
     *          }
     *      },
     *      {
     *        "id": 2,
     *        "organization_id": 2,
     *        "name": "Evaluation",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 2
     *          }
     *      }
     *    ]
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
            $workflows = Workflow::with(['organization', 'stages'])->get();
            if ($workflows->count() === 0) {
                return response()->json('', 204);
            }

            $data = $workflows->toArray();
        } else {
            $organizations = Organization::all()->toArray();
            $collectedIds  = collectIds($organizations, $res);
            $workflows     = Workflow::with(['organization', 'stages'])
                ->whereIn('organization_id', $collectedIds)
                ->get();
            if ($workflows->count() === 0) {
                return response()->json('', 204);
            }

            $data = $workflows->toArray();
        }
        return response()->json(
            $this->resp(
                200,
                'Workflows.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created Stage in DB
     *
     * @param StoreWorkflowRequest $request Request
     *
     * @return JsonResponse
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Workflow.store. Result is successful."
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
     */
    public function store(StoreWorkflowRequest $request)
    {
        $workflow = new Workflow();

        $workflow->name            = $request->get('name');
        $workflow->organization_id = $request->get('organization_id');
        $workflow->workflow_type   = $request->get('workflow_type');
        $workflow->description     = $request->get('description');

        // check permission to organization_id from the request
        $response = $this->_checkPermissionToOrganizationId($workflow);
        if ($response !== true) {
            return response()->json($response, 454);
        }

        if ($workflow->save()) {
            $stages = $request->stages;
            $this->storeWorkflowStages($workflow->id, $stages);

            return response()->json(
                $this->resp(
                    200,
                    'Workflows.store'
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
     * Store data in pivot table
     *
     * @param $workflowId
     * @param $stages
     *
     * @return bool
     */
    private function storeWorkflowStages($workflowId, $stages)
    {
        $arr      = $this->formatStages($stages);
        $workflow = Workflow::find($workflowId);
        $workflow->stages()->attach($arr);

        return true;
    }

    /**
     * @param $workflowId
     * @param $stages
     *
     * @return bool
     */
    private function updateWorkflowStages($workflowId, $stages)
    {
        $arr      = $this->formatStages($stages);
        $workflow = Workflow::find($workflowId);
        $workflow->stages()->sync($arr);

        return true;
    }

    /**
     * @param $stages
     *
     * @return array
     */
    private function formatStages($stages)
    {
        $arr = [];
        foreach ($stages as $stage) {
            $arr[$stage['id']] = ['order' => $stage['order']];
        }

        return $arr;
    }

    /**
     * Show the specified Stage.
     *
     * @param int $id ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object",
     *    "stages": [{
     *        "id": 1,
     *        "organization_id": 2,
     *        "name": "Documenting",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 1
     *          }
     *      },
     *      {
     *        "id": 2,
     *        "organization_id": 2,
     *        "name": "Evaluation",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 2
     *          }
     *      }]
     *  },
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
        $workflow = Workflow::with(['organization', 'stages'])
            ->whereId($id)->first();

        if (!$workflow) {
            return response()->json(
                $this->resp(
                    456,
                    'Workflows.show'
                ),
                456
            );
        }

        $data = $workflow->toArray();

        return response()->json(
            $this->resp(
                200,
                'Workflows.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified Workflow.
     *
     * @param UpdateWorkflow $request Request
     * @param int            $id      ID
     *
     * @return JsonResponse
     * @response 200 {
     *  "success": true,
     *  "data": {
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object",
     *    "stages": [{
     *        "id": 1,
     *        "organization_id": 2,
     *        "name": "Documenting",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 1
     *          }
     *      },
     *      {
     *        "id": 2,
     *        "organization_id": 2,
     *        "name": "Evaluation",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 2
     *          }
     *      }]
     *    },
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
     */
    public function update(UpdateWorkflow $request, $id)
    {
        $workflow = Workflow::with(['organization', 'stages'])
            ->whereId($id)->first();

        if (!$workflow) {
            return response()->json(
                $this->resp(
                    456,
                    'Workflows.update'
                ),
                456
            );
        }

        $workflow->fill($request->all());

        if ($workflow->save()) {
            $stages = $request->stages;
            $this->updateWorkflowStages($workflow->id, $stages);

            $workflow = Workflow::with(['organization', 'stages'])
                ->whereId($id)->first();

            return response()->json(
                $this->resp(
                    200,
                    'Workflows.update',
                    $workflow
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
        $workflow = Workflow::whereId($id)->first();

        if (!$workflow) {
            return response()->json(
                $this->resp(
                    456,
                    'Workflows.softDestroy'
                ),
                456
            );
        }

        if ($workflow->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'Workflows.softDestroy'
                ),
                200
            );
        } else {
            return $this->response->error(
                '"Could not delete Workflow."',
                500
            );
        }
    }

    /**
     * Get index of soft-deleted Workflows
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Stages.indexSoftDeleted. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object",
     *    "stages": [{
     *        "id": 1,
     *        "organization_id": 2,
     *        "name": "Documenting",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 1
     *          }
     *      },
     *      {
     *        "id": 2,
     *        "organization_id": 2,
     *        "name": "Evaluation",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 2
     *          }
     *      }]
     *   },
     *   {
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "organization_id": 2,
     *    "workflow_type": "request",
     *    "description": "",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03",
     *    "organization": "object",
     *    "stages": [{
     *        "id": 1,
     *        "organization_id": 2,
     *        "name": "Documenting",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 1
     *          }
     *      },
     *      {
     *        "id": 2,
     *        "organization_id": 2,
     *        "name": "Evaluation",
     *        "workflow_type": "request",
     *        "description": null,
     *        "pivot": {
     *              "order": 2
     *          }
     *      }]
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
        $workflows = Workflow::with(['organization', 'stages'])
            ->onlyTrashed()->get();

        if (!$workflows->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'Workflows.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'Workflows.indexSoftDeleted',
                $workflows
            ),
            200
        );
    }

    /**
     * Restore Workflow
     *
     * @param $id int ID
     *
     * @queryParam id int required Workflow ID
     *
     * @response   200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Stages.restore. Result is successful.",
     *  "data": null
     * }
     *
     * @response   453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @response   456 {
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
        $workflow = Workflow::onlyTrashed()->whereId($id)->first();

        if (!$workflow) {
            return response()->json(
                $this->resp(
                    456,
                    'Workflows.restore'
                ),
                456
            );
        }

        // Restore user-details
        $workflow->restore();

        return response()->json(
            $this->resp(200, 'Workflows.restore'),
            200
        );
    }

    /**
     * Destroy Workflow permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required Workflow ID
     *
     * @response   200 {
     *  "success": true,
     *  "message": "Workflows.destroyPermanently. Result is successful."
     * }
     *
     * @response   453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @response   456 {
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
        $workflow = Workflow::withTrashed()->whereId($id)->first();
        if (!$workflow) {
            return response()->json(
                $this->resp(456, 'Workflows.destroyPermanently'),
                456
            );
        }

        $workflow->forceDelete();

        return response()->json(
            $this->resp(200, 'Workflows.destroyPermanently'),
            200
        );
    }
}
