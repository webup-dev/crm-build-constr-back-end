<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User_profile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Auth;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Api\V1\Traits\ApiResponsesVadis;

/**
 * @group Organizations
 */
class OrganizationsController extends Controller
{
    use Helpers;
    use ApiResponsesVadis;

    public function __construct()
    {
        $this->middleware('organizations_organization.admin')->only(['index', 'store', 'show', 'update', 'softDestroy', 'indexSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get index of organizations
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "level": 0,
     *    "order": 1,
     *    "name": "Platform",
     *    "parent_id": null,
     *    "deleted_a": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 1,
     *    "level": 1,
     *    "order": 1,
     *    "name": "WNY",
     *    "parent_id": 1,
     *    "deleted_a": null,
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
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @return Response
     */
    public function index()
    {
        // get user organization_id
        $parentId = $this->userOrganizationId();

        $organizations = Organization::all();

        if ($organizations->count() === 0) {

            $response = [
                'success' => true,
                'message' => "Organizations are absent."
            ];

            return response()->json($response, 204);
        }

        // check organizational roles
        $organizations = $this->getParentAndChildsByParentId($organizations, $parentId);

        $data = $organizations->toArray();

        // reindex to prevent absence of 0 key that is needed for tests
        $data = array_values($data);

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => "Organizations retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    private function userOrganizationId()
    {
        $user        = Auth::guard()->user();
        $userProfile = User_profile::with('organization')
            ->whereUserId($user->id)
            ->first();

        $parentId = $userProfile->organization->id;
        if ($parentId === null) {
            $parentId = 0;
        }

        return $parentId;
    }

    private function getParentAndChildsByParentId(Collection $organizations, $parentId)
    {
        // array of objects to array
        $sourceArr = $organizations->toArray();

        // get required arrays
        $requiredArr = buildTree($sourceArr, $parentId);

        // transform selected arrays into the plain array of id's. Add parent Id
        $ids   = collectValues($requiredArr, 'id', []);
        $ids[] = $parentId;

        // select required objects
        $selected = $organizations->whereIn('id', $ids);

        return $selected;
    }

    /**
     * Get index of soft-deleted roles
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Soft-deleted Roles are retrieved successfully.",
     *  "data": [{
     *    "id": 1,
     *    "level": 1,
     *    "order": 1,
     *    "name": "Winter",
     *    "parent_id": 1,
     *    "deleted_a": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "level": 1,
     *    "order": 2,
     *    "name": "Autumn",
     *    "parent_id": 1,
     *    "deleted_a": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }]
     * }
     *
     * @response 204 {
     *  "message": "No content."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
     * }
     *
     * @return Response
     */
    public function indexSoftDeleted()
    {
        $organizations = Organization::onlyTrashed()
            ->select('id', 'level', 'order', 'name', 'parent_id', 'deleted_at', 'created_at', 'updated_at')
            ->get();

        if (!$organizations->count()) {
            $response = [
                'success' => true,
                'message' => "Soft Deleted Customers are empty."
            ];

            return response()->json($response, 204);
        }

        foreach ($organizations as $organization) {
            $organization->subline = $this->getParentBranch($organization);
        }

        $data = $organizations->toArray();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "Soft-deleted customers are retrieved successfully.",
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    private function getParentBranch(Organization $organization)
    {
        $parentId = $organization->parent_id;
        $subline = '';

        while ($parentId !== null) {
            $parent = Organization::withTrashed()->whereId($parentId)->first();
            $parentId = $parent->parent_id;
            if ($parentId != null) {
                if ($subline === '') {
                    $subline = $parent->name;
                } else {
                    $subline = $parent->name . ':' . $subline;
                }
            }
        }
        return $subline;
    }

    /**
     * Get the specified Structure Item.
     *
     * @queryParam id required Item ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Item is retrieved successfully.",
     *  "data": {
     *    "id": 1,
     *    "level": 1,
     *    "order": 1,
     *    "name": "WNY",
     *    "parent_id": 1,
     *    "deleted_a": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *  },
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
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
     *  "message": "Item is absent",
     *  "data": null,
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $organization = Organization::whereId($id)->first();
        if (!$organization) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => "Item is absent.",
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $data = $organization->toArray();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => 'Item is retrieved successfully.',
            'data'    => $data
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
     * @response 422 {
     *  "error": [
     *      "message",
     *      "errors"
     *   ]
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent."
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
            'order'     => 'required|integer',
            'parent_id' => 'integer|nullable',
        );

        $messages = array(
            'name.required'     => 'Please enter a name.',
            'name.string'       => 'Name must be a string.',
            'order.required'    => 'Please enter an order.',
            'order.string'      => 'Order must be a string.',
            'parent_id.integer' => 'Parent ID must be an integer.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            $response = [
                'errors' => [
                    'errors'  => $errors,
                    'message' => 'The given data was invalid.'
                ]
            ];
            return response()->json($response, 452);
        }

        $organization = new Organization();

        // Check parent ID
        $parentId = $request->get('parent_id');

        // adapting js value (00 to php's null)
        if ($parentId != 0) {
            $org = Organization::whereId($parentId)->first();
//            dd($org);

            $organization->level = $org->level + 1;
            if (!$org) {
                $response = [
                    'success' => false,
                    'message' => 'Parent Id is impossible.'
                ];
                return response()->json($response, 452);
            }
        } else {
            $parentId            = null;
            $organization->level = 1;
        }

        $organization->name      = $request->get('name');
        $organization->parent_id = $parentId;
        $organization->order     = $request->get('order');

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
     *    "id": 1,
     *    "level": 1,
     *    "order": 1,
     *    "name": "WNY",
     *    "parent_id": 1,
     *    "deleted_a": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *  },
     *  "message": "Organization is updated successfully."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "The given data was invalid."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 455 {
     *  "success": false,
     *  "message": "ID is absent."
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
        $rules = array(
            'name'      => 'required|string',
            'order'     => 'required|integer',
            'parent_id' => 'integer|nullable',
        );

        $messages = array(
            'name.required'     => 'Please enter a name.',
            'name.string'       => 'Name must be a string.',
            'order.required'    => 'Please enter an order.',
            'order.string'      => 'Order must be a string.',
            'parent_id.integer' => 'Parent ID must be an integer.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            $response = [
                'errors' => [
                    'errors'  => $errors,
                    'message' => 'The given data was invalid.'
                ]
            ];
            return response()->json($response, 452);
        }

        $organization = Organization::whereId($id)->first();

        if (!$organization) {
            $response = [
                'success' => false,
                'message' => 'Could not find Organization.'
            ];

            return response()->json($response, 452);
        }

        $organization->fill($request->all());

        $parentOrg           = Organization::whereId($organization->parent_id)->first();
        $organization->level = $parentOrg->level + 1;

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
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 455 {
     *  "success": false,
     *  "message": "Id is absent."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "message": "Impossible to destroy due to child."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function softDestroy($id)
    {
        $organization = Organization::whereId($id)->first();

        if (!$organization) {
            $response = [
                'success' => false,
                'message' => 'Id is absent.'
            ];

            return response()->json($response, 455);
        }

        $child = Organization::whereParentId($organization->id)->first();
        if ($child) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Impossible to destroy due to child.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        // check customers
        $customer = Customer::whereOrganizationId($organization->id)->first();

        if ($customer) {
            return response()->json($this->apiResponses(462), 462);
        }

        // check profiles
        $profile = User_profile::whereDepartmentId($organization->id)->first();

        if ($profile) {
            return response()->json($this->apiResponses(462, "There is profile."), 462);
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

    /**
     * Restore customer
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required Organization ID
     *
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Organization is restored.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
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
     *  "message": "Incorrect the Entity ID in the URL ",
     *  "data": null
     * }
     *
     * @param $id
     * @return void
     */
    public function restore($id)
    {
        $organization = Organization::onlyTrashed()->whereId($id)->first();

        if (!$organization) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        if ($organization->parent_id) {
            $parent = Organization::onlyTrashed()->whereId($organization->parent_id)->first();
            if ($parent) {
                $response = [
                    'success' => false,
                    'code'    => 455,
                    'message' => 'There is a parent soft-deleted organization.',
                    'data'    => null
                ];

                return response()->json($response, 455);
            }
        }

        // Restore organization
        $organization->restore();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => 'Organization is restored successfully.',
            'data'    => null
        ];

        return response()->json($response, 200);
    }

    /**
     * Destroy organization permanently
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Correct permanent destroy.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 455 {
     *  "success": false,
     *  "code": 455,
     *  "message": "There is soft-deleted organization.",
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
     * @param $id
     * @return void
     */
    public
    function destroyPermanently($id)
    {
        $organization = Organization::withTrashed()->whereId($id)->first();

        if (!$organization) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $child = Organization::onlyTrashed()->whereParentId($organization->id)->first();
        if ($child) {
            $response = [
                'success' => false,
                'code'    => 455,
                'message' => 'There is a child soft-deleted organization.',
                'data'    => null
            ];

            return response()->json($response, 455);
        }

        $organization->forceDelete();
        $response = [
            'success' => true,
            'code' => 200,
            'message' => 'Organization is deleted permanently.',
            'data' => null
        ];

        return response()->json($response, 200);
    }
}
