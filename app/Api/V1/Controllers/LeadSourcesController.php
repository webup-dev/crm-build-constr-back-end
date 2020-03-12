<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreLeadSource;
use App\Api\V1\Requests\UpdateLeadSource;
use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
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
 * @group    LeadSources
 */
class LeadSourcesController extends Controller
{
    use Helpers;
    use Responses;

    /**
     * Get index of roles
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Lead Sources.index. Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "name": "Blogging",
     *    "description": "Description 1",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Blogging 2",
     *    "description": "Description 2",
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $leadSources = LeadSource::all();
        if ($leadSources->count() === 0) {
            return response()->json('', 204);
        }

        $data = $leadSources->toArray();

        return response()->json(
            $this->resp(
                200,
                'Lead Sources.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created LeadSource in DB
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadSource.store. Result is successful."
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
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Role",
     *      "status_code": 500
     *    }
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLeadSource $request)
    {
        $leadSource = new LeadSource();

        $leadSource->name        = $request->get('name');
        $leadSource->description = $request->get('description');

        if ($leadSource->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LeadSource.store'
                ),
                200
            );
        } else {
            return $this->response->error(
                'Could not create role',
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
     *  "message": "Lead Sources.show. Result is successful."
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
     * @return \Illuminate\Http\JsonResponse
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
     * Update the specified resource in storage.
     *
     * @param UpdateLeadSource $request Request
     * @param int              $id      ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "LeadSource Updated",
     *       "description": "Description Updated",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36"
     *     },
     *  "message": "LeadSource.update. Result is successful."
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
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Role",
     *      "status_code": 500
     *    }
     * }
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Role",
     *      "status_code": 500
     *    }
     * }
     *
     * @return \Illuminate\Http\JsonResponse|void
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
                'Could not delete Role',
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexSoftDeleted()
    {
        $leadSources = LeadSource::onlyTrashed()->get();

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
     * Restore LeadSource
     *
     * @param $id ID
     *
     * @queryParam id int required User-Details ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LeadSources.restore. Result is successful.",
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
     *  "message": "LeadSources.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Destroy user details permanently
     *
     * @param $id int ID
     *
     * @queryParam id int required User Details ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "User Details are deleted permanently."
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPermanently($id)
    {
        $leadSources = LeadSource::withTrashed()->whereId($id)->first();
        if (!$leadSources) {
            return response()->json(
                $this->resp(456, 'LeadSources.destroyPermanently'),
                456
            );
        }

        $leadSources->forceDelete();

        return response()->json(
            $this->resp(200, 'LeadSources.destroyPermanently'),
            200
        );
    }
}
