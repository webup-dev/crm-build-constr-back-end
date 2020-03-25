<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreLeadSource;
use App\Api\V1\Requests\UpdateLeadSource;
use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use App\Models\Organization;
use App\Models\User_profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Responses;


/**
 * Controller to operate with LeadSources
 *
 * @category Controller
 * @package  LeadSources
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Lead Sources
 */
class LeadSourcesController extends Controller
{
    use Helpers;
    use Responses;

    /**
     * LeadSourcesController constructor.
     */
    public function __construct()
    {
        $this->middleware('organization.user')
            ->only(['index']);
        $this->middleware('lead-sources.organization.user')
            ->only(['show']);
        $this->middleware('common.organization.admin')
            ->only(['store']);
        $this->middleware('lead-sources.organization.admin')
            ->only(['update', 'softDestroy']);
        $this->middleware('platform.admin')
            ->only(['indexSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get index of Lead Sources
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadSources.index. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "Website - CertainTeed",
     *    "category_id": 18,
     *    "organization_id": 2,
     *    "status": "active",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Website - CertainFeed",
     *    "category_id": 18,
     *    "organization_id": 2,
     *    "status": "active",
     *    "deleted_at": null,
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
            $leadSources = LeadSource::all();
            if ($leadSources->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leadSources->toArray();
        } else {
            $organizations = Organization::all()->toArray();
            $collectedIds  = collectIds($organizations, $res);
            $leadSources   = LeadSource::whereIn('organization_id', $collectedIds)
                ->get();
            if ($leadSources->count() === 0) {
                return response()->json('', 204);
            }

            $data = $leadSources->toArray();
        }

        return response()->json(
            $this->resp(
                200,
                'LeadSources.index',
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
        $user = Auth::guard()->user();

        $roles = $user->roles;

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

    /**
     * Store a newly created LsCategory in DB
     *
     * @param StoreLeadSource $request Request
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LsCategory.store. Result is successful."
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
     *      "message": "Could not create Role",
     *      "status_code": 500
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function store(StoreLeadSource $request)
    {
        $leadSource = new LeadSource();

        $leadSource->name            = $request->get('name');
        $leadSource->category_id     = $request->get('category_id');
        $leadSource->organization_id = $request->get('organization_id');
        $leadSource->status          = $request->get('status');

        // check permission to organization_id
        $user        = Auth::guard()->user();
        $userProfile = User_profile::whereUserId($user->id)->first();
        if ($userProfile) {
            $userOrganizationId = $userProfile->department_id;
            $organizations      = Organization::all()->toArray();
            $collectedIds       = collectIds($organizations, $userOrganizationId);
            if (!in_array($leadSource->organization_id, $collectedIds)) {
                $response = [
                    'success' => false,
                    'message' => 'Permission to the department is absent.'
                ];
                return response()->json($response, 454);
            }
        }

        if ($leadSource->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadSources.store'
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not create LeadSource',
                500
            );
        }
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
     *       "name": "Lead Source 1",
     *       "description": "Description 1",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-08 13:25:36"
     *     },
     *  "message": LsCategories.show. Result is successful."
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
        $leadSource = LeadSource::whereId($id)->first();

        if (!$leadSource) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadSources.show'
                ),
                456
            );
        }

        $leadSource['category']     = $leadSource->lsCategory;
        $leadSource['organization'] = $leadSource->organization;

        $data = $leadSource->toArray();

        return response()->json(
            $this->resp(
                200,
                'LeadSources.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified Lead Source.
     *
     * @param UpdateLeadSouce $request Request
     * @param int             $id      ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "LsCategory Updated",
     *       "description": "Description Updated",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36"
     *     },
     *  "message": "LsCategory.update. Result is successful."
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
    public function update(UpdateLeadSource $request, $id)
    {
        $leadSource = LeadSource::whereId($id)->first();

        if (!$leadSource) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadSources.update'
                ),
                456
            );
        }

        $leadSource->fill($request->all());

        if ($leadSource->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadSources.update',
                    $leadSource
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
     *  "message": "Role is deleted successfully",
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
     *  "message": "Could not delete Lead Source Category."
     * }
     *
     * @return JsonResponse|void
     * @throws \Exception
     */
    public function softDestroy($id)
    {
        $leadSource = LeadSource::whereId($id)->first();

        if (!$leadSource) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadSources.softDestroy'
                ),
                456
            );
        }

        if ($leadSource->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadSources.softDestroy'
                ),
                200
            );
        } else {
            return $this->response->error(
                '"Could not delete Lead Source."',
                500
            );
        }
    }

    /**
     * Get index of soft-deleted files
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "blogging",
     *    "description": "Description text",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Social Media",
     *    "description": "Description text",
     *    "deleted_at": "2019-06-24 07:12:03",
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
        $leadSources = LeadSource::with(['lsCategory', 'organization'])
            ->onlyTrashed()->get();

        if (!$leadSources->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'LeadSources.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'LeadSources.indexSoftDeleted',
                $leadSources
            ), 200
        );
    }

    /**
     * Restore Lead Source
     *
     * @param $id int ID
     *
     * @queryParam id int required User-Details ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadSource.restore. Result is successful.",
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
     *  "message": "LeadSource.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function restore($id)
    {
        $leadSources = LeadSource::onlyTrashed()->whereId($id)->first();

        if (!$leadSources) {
            return response()->json(
                $this->resp(
                    456,
                    'LeadSources.restore'
                ),
                456
            );
        }

        // Restore user-details
        $leadSources->restore();

        return response()->json(
            $this->resp(200, 'LeadSources.restore'),
            200
        );
    }

    /**
     * Destroy Lead Source permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required Lead Source ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Lead Sources are deleted permanently."
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
        $leadSource = LeadSource::withTrashed()->whereId($id)->first();
        if (!$leadSource) {
            return response()->json(
                $this->resp(456, 'LeadSources.destroyPermanently'),
                456
            );
        }

        $leadSource->forceDelete();

        return response()->json(
            $this->resp(200, 'LeadSources.destroyPermanently'),
            200
        );
    }
}
