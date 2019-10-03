<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Organizations
 */
class OrganizationsController extends Controller
{
    use Helpers;

    /**
     * Get index of organizations
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "order": 1,
     *    "name": "Central Office",
     *    "parent_id": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "order": 2,
     *    "name": "Department 1",
     *    "parent_id": 1,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Organizations retrieved successfully."
     * }
     *
     * @response 204 {
     *  "success": true,
     *  "message": "Organizations are absent."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $organizations = Organization::all();

        if ($organizations->count() === 0) {
            $response = [
                'success' => true,
                'message' => "Organizations are absent."
            ];

            return response()->json($response, 200);
        }
        $data = $organizations->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => "Organizations retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created Organization
     *
     * @bodyParam name string required Organization Name
     * @bodyParam parent_id int required Parent Organization ID
     * @bodyParam order string required Order of Organizations
     *
     * @response 200 {
     *  "success": true,
     *  "message": "New Organization is created successfully."
     * }
     *
     * @response 452 {
     *  "success": false,
     *  "message": "Parent Id is impossible."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Organization",
     *      "status_code": 500
     *    }
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name'      => 'required|string',
            'order'     => 'required|string',
            'parent_id' => 'integer',
        );

        $messages = array(
            'name.required'  => 'Please enter a name.',
            'name.string'    => 'Name must be a string.',
            'order.required' => 'Please enter an order.',
            'order.string'   => 'Order must be a string.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }

        // Check parent ID
        $parentId = $request->get('parent_id');
        $org = Organization::whereId($parentId)->first();

        if (!$org) {
            $response = [
                'success' => false,
                'message' => 'Parent Id is impossible.'
            ];
            return response()->json($response, 452);
        }

        $organization = new Organization();

        $organization->name        = $request->get('name');
        $organization->order = $request->get('order');
        $organization->parent_id = $request->get('parent_id');

        if ($organization->save()) {
            $response = [
                'success' => true,
                'message' => 'New Organization is created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create organization', 500);
        }
    }

    /**
     * Update the specified Organization
     *
     * @queryParam id required Organization ID
     *
     * @bodyParam name string Organization Name
     * @bodyParam parent_id int Parent Organization ID
     * @bodyParam order string Order of Organizations
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "order": 1,
     *       "name": "Central Office",
     *       "parent_id": null,
     *       "created_at": "2019-06-24 07:12:03",
     *       "updated_at": "2019-06-24 07:12:03"
     *     },
     *  "message": "Organization is updated successfully."
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Could not find Organization."
     * }
     *
     * @response 452 {
     *  "success": false,
     *  "message": "Parent Id is impossible."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Organization",
     *      "status_code": 500
     *    }
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::whereId($id)->first();

        if (!$organization) {
            $response = [
                'success' => false,
                'message' => 'Could not find Organization.'
            ];

            return response()->json($response, 452);
        }

        $organization->fill($request->all());

        if ($organization->parent_id == $id) {
            $response = [
                'success' => false,
                'message' => 'Parent Id is impossible.'
            ];

            return response()->json($response, 452);
        }

        $data = json_encode($organization);

        if ($organization->save()) {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Organization is updated successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update Role', 500);
        }
    }

    /**
     * Remove the specified Organization.
     *
     * @queryParam id required Organization ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Organization is deleted successfully"
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Could not find Organization."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $organization = Organization::whereId($id)->first();

        if (!$organization) {
            $response = [
                'success' => false,
                'message' => 'Could not find Organization.'
            ];

            return response()->json($response, 452);
        }

        if ($organization->delete()) {
            $response = [
                'success' => true,
                'message' => 'Organization is deleted successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not delete Organization', 500);
        }
    }
}
