<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreLeadRequest;
use App\Api\V1\Requests\UpdateLeadRequest;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Organization;
use App\Api\V1\Traits\CheckPermissionToOrganizationId;
use App\Traits\Responses;
use App\Traits\GetOrganizations;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Routing\Helpers;

/**
 * Controller to operate with Leads
 *
 * @category Controller
 * @package  Leads
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Leads
 */
class LeadsController extends Controller
{
    use Helpers;
    use Responses;
    use CheckPermissionToOrganizationId;
    use GetOrganizations;

    /**
     * LeadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('organizations_organization.admin')
            ->only(
                [
                    'softDestroy',
                    'indexSoftDeleted',
                    'restore',
                    'destroyPermanently'
                ]
            );
        $this->middleware('OrganizationUserWithOrganizationId')
            ->only(
                [
                    'index',
                    'show',
                    'store',
                    'getListOfOrganizations',
                    'update',
                ]
            );
    }

    /**
     * Get index of Leads
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Leads.index. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "organization_id": 2,
     *    "due_date": "2019-06-24 07:12:03",
     *    "anticipated_project_date": "2019-06-24 07:12:03",
     *    "lead_type_id": 1,
     *    "lead_status_id": 1,
     *    "declined_reason_other": null,
     *    "lead_source_id": 1,
     *    "stage_id": 1,
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "requester_id": 1,
     *    "note": "Note #1.",
     *    "lead_owner_id": 1,
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "leadType": "object",
     *    "leadStatus": "object",
     *    "leadSource": "object",
     *    "stage": "object",
     *    "requester": "object",
     *    "owner": "object",
     *    "creator": "object",
     *    "editor": "object",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "organization_id": 2,
     *    "due_date": "2019-06-24 07:12:03",
     *    "anticipated_project_date": "2019-06-24 07:12:03",
     *    "lead_type_id": 1,
     *    "lead_status_id": 1,
     *    "declined_reason_other": null,
     *    "lead_source_id": 1,
     *    "stage_id": 1,
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "requester_id": 1,
     *    "note": "Note #1.",
     *    "lead_owner_id": 1,
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "leadType": "object",
     *    "leadStatus": "object",
     *    "leadSource": "object",
     *    "stage": "object",
     *    "requester": "object",
     *    "owner": "object",
     *    "creator": "object",
     *    "editor": "object",
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
    public function index()
    {
        $res = $this->_getDepartmentId();
        if ($res === true) {
            $leads = Lead::with([
                'organization',
                'leadType',
                "leadStatus",
                "leadSource",
                "stage",
                "requester",
                "owner",
                'creator',
                'editor'
            ])
                ->get();
            if ($leads->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leads->toArray();
        } else {
            $organizations = Organization::all()->toArray();
            $collectedIds  = collectIds($organizations, $res);
            $leads         = Lead::with([
                'organization',
                'leadType',
                "leadStatus",
                "leadSource",
                "stage",
                "requester",
                "owner",
                'creator',
                'editor'
            ])
                ->whereIn('organization_id', $collectedIds)
                ->get();
            if ($leads->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leads->toArray();
        }

        return response()->json(
            $this->resp(
                200,
                'Leads.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created Lead in DB
     *
     * @param StoreLeadRequest $request Request
     *
     * @return JsonResponse
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Leads.store. Result is successful."
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
     */
    public function store(StoreLeadRequest $request)
    {
        $data = $request->all();

        $lead = new Lead();
        $lead->fill($data);

        // check permission to organization_id from the request
        $response = $this->_checkPermissionToOrganizationId($lead);
        if ($response !== true) {
            return response()->json($response, 454);
        }

        if ($lead->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'Leads.store'
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not create Lead',
                500
            );
        }
    }

    /**
     * Show the specified Lead.
     *
     * @param int $id ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *    "id": 1,
     *    "organization_id": 2,
     *    "due_date": "2019-06-24 07:12:03",
     *    "anticipated_project_date": "2019-06-24 07:12:03",
     *    "lead_type_id": 1,
     *    "lead_status_id": 1,
     *    "declined_reason_other": null,
     *    "lead_source_id": 1,
     *    "stage_id": 1,
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "requester_id": 1,
     *    "note": "Note #1.",
     *    "lead_owner_id": 1,
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "leadType": "object",
     *    "leadStatus": "object",
     *    "leadSource": "object",
     *    "stage": "object",
     *    "requester": "object",
     *    "owner": "object",
     *    "creator": "object",
     *    "editor": "object",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *  },
     *  "message": "Leads.show. Result is successful."
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
        $lead = Lead::with([
            'organization',
            'leadType',
            "leadStatus",
            "leadSource",
            "stage",
            "requester",
            "owner",
            'creator',
            'editor'
        ])
            ->whereId($id)->first();

        if (!$lead) {
            return response()->json(
                $this->resp(
                    456,
                    'Leads.show'
                ),
                456
            );
        }

        $data = $lead->toArray();

        return response()->json(
            $this->resp(
                200,
                'Leads.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified Leads.
     *
     * @param UpdateLeadRequest $request Request
     * @param int               $id      ID
     *
     * @return JsonResponse
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "data": null,
     *  "message": "Leads.update. Result is successful."
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
     */
    public function update(UpdateLeadRequest $request, $id)
    {
        $lead = Lead::whereId($id)->first();

        if (!$lead) {
            return response()->json(
                $this->resp(
                    456,
                    'Leads.update'
                ),
                456
            );
        }

        $lead->fill($request->all());

        if ($lead->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'Leads.update',
                    $lead
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not update Lead',
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
     *  "message": "Leads.softDestroy. Result is successful.",
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
        $lead = Lead::whereId($id)->first();

        if (!$lead) {
            return response()->json(
                $this->resp(
                    456,
                    'Leads.softDestroy'
                ),
                456
            );
        }

        if ($lead->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'Leads.softDestroy'
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
     * Get index of soft-deleted Leads
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Leads.indexSoftDeleted. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "organization_id": 2,
     *    "due_date": "2019-06-24 07:12:03",
     *    "anticipated_project_date": "2019-06-24 07:12:03",
     *    "lead_type_id": 1,
     *    "lead_status_id": 1,
     *    "declined_reason_other": null,
     *    "lead_source_id": 1,
     *    "stage_id": 1,
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "requester_id": 1,
     *    "note": "Note #1.",
     *    "lead_owner_id": 1,
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "leadType": "object",
     *    "leadStatus": "object",
     *    "leadSource": "object",
     *    "stage": "object",
     *    "requester": "object",
     *    "owner": "object",
     *    "creator": "object",
     *    "editor": "object",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "organization_id": 2,
     *    "due_date": "2019-06-24 07:12:03",
     *    "anticipated_project_date": "2019-06-24 07:12:03",
     *    "lead_type_id": 1,
     *    "lead_status_id": 1,
     *    "declined_reason_other": null,
     *    "lead_source_id": 1,
     *    "stage_id": 1,
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "requester_id": 1,
     *    "note": "Note #1.",
     *    "lead_owner_id": 1,
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "leadType": "object",
     *    "leadStatus": "object",
     *    "leadSource": "object",
     *    "stage": "object",
     *    "requester": "object",
     *    "owner": "object",
     *    "creator": "object",
     *    "editor": "object",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
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
        $leads = Lead::with([
            'organization',
            'leadType',
            "leadStatus",
            "leadSource",
            "stage",
            "requester",
            "owner",
            'creator',
            'editor'
        ])
            ->onlyTrashed()->get();

        if (!$leads->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'Leads.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'Leads.indexSoftDeleted',
                $leads
            ),
            200
        );
    }

    /**
     * Restore Leads
     *
     * @param $id int ID
     *
     * @queryParam id int required Leads ID
     *
     * @response   200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Leads.restore. Result is successful.",
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
     *  "message": "Leads.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function restore($id)
    {
        $lead = Lead::onlyTrashed()->whereId($id)->first();

        if (!$lead) {
            return response()->json(
                $this->resp(
                    456,
                    'Leads.restore'
                ),
                456
            );
        }

        // Restore user-details
        $lead->restore();

        return response()->json(
            $this->resp(200, 'Leads.restore'),
            200
        );
    }

    /**
     * Destroy Leads permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required Leads ID
     *
     * @response   200 {
     *  "success": true,
     *  "message": "Leads.destroyPermanently. Result is successful."
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
        $lead = Lead::withTrashed()->whereId($id)->first();
        if (!$lead) {
            return response()->json(
                $this->resp(456, 'Leads.destroyPermanently'),
                456
            );
        }

        $lead->forceDelete();

        return response()->json(
            $this->resp(200, 'Leads.destroyPermanently'),
            200
        );
    }
}
