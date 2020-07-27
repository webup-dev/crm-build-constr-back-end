<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreLsCategory;
use App\Api\V1\Requests\UpdateLsCategory;
use App\Http\Controllers\Controller;
use App\Models\LsCategory;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Routing\Helpers;
use App\Traits\Responses;


/**
 * Controller to operate with LeadSources
 *
 * @category Controller
 * @package  LeadSources
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Lead Source Categories
 */
class LsCategoriesController extends Controller
{
    use Helpers;
    use Responses;

    /**
     * Get index of roles
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LsCategories.index. Result is successful.",
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
     * @return JsonResponse
     */
    public function index()
    {
        $leadSources = LsCategory::all();
        if ($leadSources->count() === 0) {
            return response()->json('', 204);
        }

        $data = $leadSources->toArray();

        return response()->json(
            $this->resp(
                200,
                'LsCategories.index',
                $data
            ),
            200
        );
    }

    /**
     * Store a newly created LsCategory in DB
     *
     * @param StoreLsCategory $request Request
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
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Role",
     *      "status_code": 500
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function store(StoreLsCategory $request)
    {
        $leadSource = new LsCategory();

        $leadSource->name        = $request->get('name');
        $leadSource->description = $request->get('description');

        if ($leadSource->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LsCategory.store'
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
     *  "message": "LsCategories.show. Result is successful."
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
        $leadSource = LsCategory::whereId($id)->first();

        if (!$leadSource) {
            return response()->json(
                $this->resp(
                    456,
                    'LsCategories.show'
                ),
                456
            );
        }

        $data = $leadSource->toArray();

        return response()->json(
            $this->resp(
                200,
                'LsCategories.show',
                $data
            ),
            200
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLsCategory $request Request
     * @param int              $id      ID
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
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Role",
     *      "status_code": 500
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function update(UpdateLsCategory $request, $id)
    {
        $leadSource = LsCategory::whereId($id)->first();

        if (!$leadSource) {
            return response()->json(
                $this->resp(
                    456,
                    'LsCategories.update'
                ),
                456
            );
        }

        $leadSource->fill($request->all());

        if ($leadSource->save()) {
            return response()->json(
                $this->resp(
                    200,
                    'LsCategories.update',
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
     * @return JsonResponse|void
     * @throws \Exception
     */
    public function softDestroy($id)
    {
        $leadSource = LsCategory::whereId($id)->first();

        if (!$leadSource) {
            return response()->json(
                $this->resp(
                    456,
                    'LsCategories.softDestroy'
                ),
                456
            );
        }

        if ($leadSource->delete()) {
            return response()->json(
                $this->resp(
                    200,
                    'LsCategories.softDestroy'
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
     * @return JsonResponse
     */
    public function indexSoftDeleted()
    {
        $leadSources = LsCategory::onlyTrashed()->get();

        if (!$leadSources->count()) {
            return response()->json(
                $this->resp(
                    204,
                    'LsCategories.indexSoftDeleted'
                ),
                204
            );
        }

        return response()->json(
            $this->resp(
                200,
                'LsCategories.indexSoftDeleted',
                $leadSources
            ), 200
        );
    }

    /**
     * Restore LsCategory
     *
     * @param $id int ID
     *
     * @queryParam id int required User-Details ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "LsCategorys.restore. Result is successful.",
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
     *  "message": "LsCategorys.restore. Incorrect ID in the URL.",
     *  "data": null
     * }
     *
     * @return JsonResponse
     */
    public function restore($id)
    {
        $leadSources = LsCategory::onlyTrashed()->whereId($id)->first();

        if (!$leadSources) {
            return response()->json(
                $this->resp(
                    456,
                    'LsCategories.restore'
                ),
                456
            );
        }

        // Restore user-details
        $leadSources->restore();

        return response()->json(
            $this->resp(200, 'LsCategories.restore'),
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
     * @return JsonResponse
     */
    public function destroyPermanently($id)
    {
        $leadSources = LsCategory::withTrashed()->whereId($id)->first();
        if (!$leadSources) {
            return response()->json(
                $this->resp(456, 'LsCategories.destroyPermanently'),
                456
            );
        }

        $leadSources->forceDelete();

        return response()->json(
            $this->resp(200, 'LsCategories.destroyPermanently'),
            200
        );
    }
}
