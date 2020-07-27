<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreRequesterRequest;
use App\Api\V1\Requests\StoreWorkflowRequest;
use App\Api\V1\Requests\UpdateRequesterRequest;
use App\Api\V1\Requests\UpdateWorkflow;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Api\V1\Traits\CheckPermissionToOrganizationId;
use App\Models\Requester;
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
 * @group    Requesters
 */
class RequestersController extends Controller
{
    use Helpers;
    use Responses;
    use CheckPermissionToOrganizationId;
    use GetOrganizations;

    /**
     * RequestersController constructor.
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
     * Get index of Requesters
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Requesters.index. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "organization_id": 2,
     *    "first_name": "Evelyn",
     *    "last_name": "Perkins",
     *    "prefix": "Mrs",
     *    "suffix": "M.D.",
     *    "email_work": "Central.Hospital@example.com",
     *    "email_personal": "evelyn.perkins@example.com",
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "phone_home": "0119627516",
     *    "phone_work": "0119627522",
     *    "phone_extension": "123",
     *    "phone_mob1": "0814540666",
     *    "phone_mob2": "0814540667",
     *    "phone_fax": "0119627523",
     *    "website": "website1.com",
     *    "other_source": "Other source 1",
     *    "note": "Note #1.",
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "created_by": "object",
     *    "updated_by": "object",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "organization_id": 2,
     *    "first_name": "Evelyn",
     *    "last_name": "Perkins",
     *    "prefix": "Mrs",
     *    "suffix": "M.D.",
     *    "email_work": "Central.Hospital@example.com",
     *    "email_personal": "evelyn.perkins@example.com",
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "phone_home": "0119627516",
     *    "phone_work": "0119627522",
     *    "phone_extension": "123",
     *    "phone_mob1": "0814540666",
     *    "phone_mob2": "0814540667",
     *    "phone_fax": "0119627523",
     *    "website": "website1.com",
     *    "other_source": "Other source 1",
     *    "note": "Note #1.",
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "created_by": "object",
     *    "updated_by": "object",
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
            $requesters = Requester::with(['organization', 'createdBy', 'updatedBy'])
                ->get();
            if ($requesters->count() === 0) {
                return response()->json('', 204);
            }

            $data = $requesters->toArray();
        } else {
            $organizations = Organization::all()->toArray();
            $collectedIds  = collectIds($organizations, $res);
            $requesters    = Requester::with([
                'organization', 'createdBy', 'updatedBy'
            ])
                ->whereIn('organization_id', $collectedIds)
                ->get();
            if ($requesters->count() === 0) {
                return response()->json('', 204);
            }

            $data = $requesters->toArray();
        }

        return response()->json(
            $this->resp(
                200,
                'Requesters.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created Stage in DB
     *
     * @param StoreRequesterRequest $request Request
     *
     * @return JsonResponse
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Requesters.store. Result is successful."
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
    public function store(StoreRequesterRequest $request)
    {
        $data = $request->all();

        $requester = new Requester();
        $requester->fill($data);

        // check permission to organization_id from the request
        $response = $this->_checkPermissionToOrganizationId($requester);
        if ($response !== true) {
            return response()->json($response, 454);
        }

        if ($requester->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'Requesters.store'
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not create Requester',
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
     *    "id": 2,
     *    "organization_id": 2,
     *    "first_name": "Evelyn",
     *    "last_name": "Perkins",
     *    "prefix": "Mrs",
     *    "suffix": "M.D.",
     *    "email_work": "Central.Hospital@example.com",
     *    "email_personal": "evelyn.perkins@example.com",
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "phone_home": "0119627516",
     *    "phone_work": "0119627522",
     *    "phone_extension": "123",
     *    "phone_mob1": "0814540666",
     *    "phone_mob2": "0814540667",
     *    "phone_fax": "0119627523",
     *    "website": "website1.com",
     *    "other_source": "Other source 1",
     *    "note": "Note #1.",
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "created_by": "object",
     *    "updated_by": "object",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *  },
     *  "message": "Requesters.show. Result is successful."
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
        $requester = Requester::with(['organization', 'createdBy', 'updatedBy'])
            ->whereId($id)->first();

        if (!$requester) {
            return response()->json(
                $this->resp(
                    456,
                    'Requesters.show'
                ),
                456
            );
        }

        $data = $requester->toArray();

        return response()->json(
            $this->resp(
                200,
                'Requesters.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified Requester.
     *
     * @param UpdateRequesterRequest $request Request
     * @param int                    $id      ID
     *
     * @return JsonResponse
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "data": null,
     *  "message": "Requesters.update. Result is successful."
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
    public function update(UpdateRequesterRequest $request, $id)
    {
        $requester = Requester::whereId($id)->first();

        if (!$requester) {
            return response()->json(
                $this->resp(
                    456,
                    'Requesters.update'
                ),
                456
            );
        }

        $requester->fill($request->all());

        if ($requester->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'Requesters.update',
                    $requester
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
     *  "message": "Requesters.softDestroy. Result is successful.",
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
        $requester = Requester::whereId($id)->first();

        if (!$requester) {
            return response()->json(
                $this->resp(
                    456,
                    'Requesters.softDestroy'
                ),
                456
            );
        }

        if ($requester->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'Requesters.softDestroy'
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
     * Get index of soft-deleted Requesters
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Requesters.indexSoftDeleted. Result is successful.",
     *  "data": [{
     *    "id": 2,
     *    "organization_id": 2,
     *    "first_name": "Evelyn",
     *    "last_name": "Perkins",
     *    "prefix": "Mrs",
     *    "suffix": "M.D.",
     *    "email_work": "Central.Hospital@example.com",
     *    "email_personal": "evelyn.perkins@example.com",
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "phone_home": "0119627516",
     *    "phone_work": "0119627522",
     *    "phone_extension": "123",
     *    "phone_mob1": "0814540666",
     *    "phone_mob2": "0814540667",
     *    "phone_fax": "0119627523",
     *    "website": "website1.com",
     *    "other_source": "Other source 1",
     *    "note": "Note #1.",
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "created_by": "object",
     *    "updated_by": "object",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "organization_id": 2,
     *    "first_name": "Evelyn",
     *    "last_name": "Perkins",
     *    "prefix": "Mrs",
     *    "suffix": "M.D.",
     *    "email_work": "Central.Hospital@example.com",
     *    "email_personal": "evelyn.perkins@example.com",
     *    "line_1": "9278 new road",
     *    "line_2": "app 3",
     *    "city": "Kilcoole",
     *    "state": "OH",
     *    "zip": "93027",
     *    "phone_home": "0119627516",
     *    "phone_work": "0119627522",
     *    "phone_extension": "123",
     *    "phone_mob1": "0814540666",
     *    "phone_mob2": "0814540667",
     *    "phone_fax": "0119627523",
     *    "website": "website1.com",
     *    "other_source": "Other source 1",
     *    "note": "Note #1.",
     *    "created_by_id": 6,
     *    "updated_by_id": 10,
     *    "deleted_at": null,
     *    "organization": "object",
     *    "created_by": "object",
     *    "updated_by": "object",
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
        $requesters = Requester::with(['organization', 'createdBy', 'updatedBy'])
            ->onlyTrashed()->get();

        if (!$requesters->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'Requesters.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'Requesters.indexSoftDeleted',
                $requesters
            ),
            200
        );
    }

    /**
     * Restore Requester
     *
     * @param $id int ID
     *
     * @queryParam id int required Requester ID
     *
     * @response   200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Requesters.restore. Result is successful.",
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
     *  "message": "Requesters.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function restore($id)
    {
        $requester = Requester::onlyTrashed()->whereId($id)->first();

        if (!$requester) {
            return response()->json(
                $this->resp(
                    456,
                    'Requesters.restore'
                ),
                456
            );
        }

        // Restore user-details
        $requester->restore();

        return response()->json(
            $this->resp(200, 'Requesters.restore'),
            200
        );
    }

    /**
     * Destroy Requester permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required Requester ID
     *
     * @response   200 {
     *  "success": true,
     *  "message": "Requesters.destroyPermanently. Result is successful."
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
        $requester = Requester::withTrashed()->whereId($id)->first();
        if (!$requester) {
            return response()->json(
                $this->resp(456, 'Requesters.destroyPermanently'),
                456
            );
        }

        $requester->forceDelete();

        return response()->json(
            $this->resp(200, 'Requesters.destroyPermanently'),
            200
        );
    }
}
