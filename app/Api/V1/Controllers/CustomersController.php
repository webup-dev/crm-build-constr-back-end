<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\StoreCustomer;
use App\Http\Requests\UpdateCustomer;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\User;
use App\Models\User_profile;
use App\Models\User_role;
use Config;
use App\Http\Controllers\Controller;
use Auth;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @group Customers
 */
class CustomersController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('customers_organization.users')->only(['index', 'store', 'show', 'update']);
        $this->middleware('customers_organization.superadmin')->only(['softDestroy']);
        $this->middleware('platform.admin')->only(['indexSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get index of customers
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "name": "Customer Test A",
     *    "organization_id": 1,
     *    "organization": "object",
     *    "type": "individual",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Customer Test B",
     *    "organization_id": 1,
     *    "organization": "object",
     *    "type": "organization",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Customers are retrieved successfully."
     * }
     *
     * @response 204 {
     *  "message": "No content."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permissions."
     * }
     *
     * @return Response
     */
    public function index()
    {
        // Define user.role to form response
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();
        $accessArray  = [
            'organization-superadmin',
            'organization-admin',
            'organization-general-manager',
            'organization-sales-manager',
            'organization-production-manager',
            'organization-administrative-leader',
            'organization-estimator',
            'organization-project-manager',
            'organization-administrative-assistant'
        ];
        if (one_from_arr_in_other_arr($accessArray, $roleNamesArr)) {
            // only its organizations
            $user         = User::find($user->id);
            $userProfile  = $user->user_profile;
            $departmentId = $userProfile->department_id;

            $customers = Customer::with('organization')
                ->select('id', 'name', 'organization_id', 'type', 'deleted_at', 'created_at', 'updated_at')
                ->where('organization_id', $departmentId)
                ->get();
        } else {
            // all organizations
            $customers = Customer::with('organization')
                ->select('id', 'name', 'organization_id', 'type', 'deleted_at', 'created_at', 'updated_at')
                ->get();
        }

        if ($customers->count() === 0) {
            $response = [
                'success' => true,
                'message' => "Customers are absent."
            ];

            return response()->json($response, 204);
        }

        $data = $customers->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => "Customers are retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    /**
     * Get index of soft-deleted customers
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "name": "Customer Test A",
     *    "organization_id": 1,
     *    "type": "individual",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Customer Test B",
     *    "organization_id": 1,
     *    "type": "individual",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Soft-deleted Customers are retrieved successfully."
     * }
     *
     * @response 204 {
     *  "message": "No content."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent."
     * }
     *
     * @return Response
     */
    public function indexSoftDeleted()
    {
        $customers = Customer::onlyTrashed()
            ->with('organization')
            ->select('id', 'name', 'type', 'organization_id', 'deleted_at', 'created_at', 'updated_at')
            ->get();

        if (!$customers->count()) {
            $response = [
                'success' => true,
                'message' => "Soft Deleted Customers are empty."
            ];

            return response()->json($response, 204);
        }

        $data = $customers->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => "Soft-deleted customers are retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    /**
     * Create customer
     *
     * @bodyParam name string required Customer Name
     * @bodyParam type string required Customer Type
     * @bodyParam organization_id integer Organization Id
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization*
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Customer is created successfully."
     * }
     *
     * @response 422 {
     *  "error": {
     *    "message": "The given data was invalid.",
     *    "errors": []
     *   }
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent."
     * }
     *
     * @param StoreCustomer $request
     * @return Response
     * @throws \Exception
     */
    public
    function store(StoreCustomer $request)
    {
        $data = $request->all();

        // Check is organization_id is available
        $user        = Auth::guard()->user();
        $userProfile = $user->user_profile;

        $organizations = Organization::all()->toArray();

        if (!isOwn($organizations, $userProfile->department_id, $data['organization_id'])) {
            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

        $customer = new Customer([
            'name'            => $data['name'],
            'organization_id' => $data['organization_id'],
            'type'            => $data['type'],
            'line_1'          => $data['line_1'],
            'line_2'          => $data['line_2'],
            'city'            => $data['city'],
            'state'           => $data['state'],
            'zip'             => $data['zip'],
        ]);

        if ($customer->save()) {
            $response = [
                'success' => true,
                'message' => 'Customer is created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create Customer', 500);
        }
    }

    /**
     * Get the specified Structure Item.
     *
     * @queryParam id required Item ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *     "id": 1,
     *     "name": "Central Office",
     *     "type": "individual",
     *     "organization_id": 1,
     *     "deleted_at": null,
     *     "created_at": "2019-12-08 13:25:36",
     *     "updated_at": "2019-12-08 13:25:36",
     *     "organization": "organization object"
     *  },
     *  "message": "Item is retrieved successfully."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "Item is absent."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permissions."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::whereId($id)
            ->first();
        if (!$customer) {
            $response = [
                'success' => false,
                'message' => "Item is absent."
            ];

            return response()->json($response, 422);
        }

        $user         = $customer->user;
        $organization = $customer->organization;

        $data = $customer->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Item is retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Edit data of the specified customer
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to users of his organization
     *
     * @bodyParam name string required Customer Name
     * @bodyParam type string required Customer Type
     * @bodyParam organization_id integer Organization Id
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *    "id": 1,
     *    "name": "Customer Test A",
     *    "organization_id": 1,
     *    "type": "individual",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *  "message": "User Profile is updated successfully."
     * }
     *
     * @response 422 {
     *  "error": {
     *    "message": "The given data was invalid.",
     *    "errors": []
     *   }
     * }
     *
     * @response 453 {
     *  "success": true,
     *  "message": "Permission is absent by the role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @param UpdateCustomer $request
     * @param $id
     * @return void
     */
    public
    function update(UpdateCustomer $request, $id)
    {
//        dd("here Controller");
        $customer = Customer::whereId($id)->first();

        if (!$customer) {
            $response = [
                'success' => false,
                'message' => 'Customer does not exist.'
            ];

            return response()->json($response, 453);
        }

        $data = $request->all();

        // Check is organization_id available
        $organizations = Organization::all()->toArray();
        $user          = Auth::guard()->user();
        $userProfile   = $user->user_profile;

        if (!isOwn($organizations, $userProfile->department_id, $data['organization_id'])) {
            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

        $customer->fill($data);

        $data = json_encode($customer);

        if ($customer->save()) {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Customer is updated successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update Customer', 500);
        }
    }

    /**
     * Soft Delete customer
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-superadmin - to users of his organization
     *
     * @queryParam id int required Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Customer is soft-deleted successfully."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "Customer is absent."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @param $id
     * @return void
     * @throws \Exception
     */
    public
    function softDestroy($id)
    {
        $customer = Customer::whereId($id)->first();
        if (!$customer) {
            $response = [
                'success' => false,
                'message' => 'Customer is absent.'
            ];

            return response()->json($response, 422);
        }

        // Check is organization_id is available
        $organizations = Organization::all()->toArray();
        $user          = Auth::guard()->user();
        $userProfile   = $user->user_profile;

        if (!isOwn($organizations, $userProfile->department_id, $customer->organization_id)) {
            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

//        $user = User::whereId($customer->user_id)->first();
//
//        // there are 3 DB tables that are bond to User: activities, user_roles, customers
//        Activity::truncate();
//
//        $userRoles = User_role::whereUserId($user->id)->get();
//        foreach ($userRoles as $userRole) {
//            $userRole->delete();
//        }

        $customer->delete();
//        $user->delete();

        $response = [
            'success' => true,
            'message' => 'Customer is soft-deleted successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Restore customer
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Customer is restored successfully."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "Customer is absent."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @param $id
     * @return void
     */
    public function restore($id)
    {
        $customer = Customer::onlyTrashed()->whereId($id)->first();

        if (!$customer) {
            $response = [
                'success' => false,
                'message' => 'Customer is absent.'
            ];

            return response()->json($response, 422);
        }

        // Check is organization_id is available
        $organizations = Organization::all()->toArray();
        $user        = Auth::guard()->user();
        $userProfile = $user->user_profile;

        if (!isOwn($organizations, $userProfile->department_id, $customer->organization_id)) {
            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

        // Restore customer
        $customer->restore();

        $response = [
            'success' => true,
            'message' => 'Customer is restored successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Destroy customer permanently
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Customer is deleted permanently."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "Customer is absent."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have permission."
     * }
     *
     * @param $id
     * @return void
     */
    public
    function destroyPermanently($id)
    {
//        $checkOrganization = $this->checkUserFromOrganization($id);

        $customer = Customer::withTrashed()->whereId($id)->first();

        if (!$customer) {
            $response = [
                'success' => false,
                'message' => 'Customer is absent.'
            ];

            return response()->json($response, 422);
        }

        $user      = User::withTrashed()->whereId($customer->user_id)->first();
        $userRoles = User_role::withTrashed()->whereUserId($customer->user_id)->get();
        $customer->forceDelete();
        foreach ($userRoles as $userRole) {
            $userRole->forceDelete();
        }
        if ($user) {
            $user->forceDelete();
        }

        $response = [
            'success' => true,
            'message' => 'Customer is deleted permanently.'
        ];

        return response()->json($response, 200);

    }

    /**
     * It gets department ID as parameter.
     * Calculates roles from authenticated user.
     * If the user is organization-superadmin or organization-admin then check the user department is equal to requested..
     *
     * @param $id
     * @return bool|\Illuminate\Http\JsonResponse
     */
    private
    function checkUserFromOrganization($id)
    {
        // check 'organization-superadmin', 'organization-admin'
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        if (one_from_arr_in_other_arr(['organization-superadmin', 'organization-admin', 'organization-general-manager'], $roleNamesArr)) {
//            print_r("here");
            $user                  = User::find($user->id);
            $userProfileRequester  = $user->user_profile;
            $departmentIdRequester = $userProfileRequester->department_id;
//            print_r("requester: " . $departmentIdRequester);
//            print_r("requester department: " . $departmentIdRequester);
//            print_r("customer department:" . $id);


            if (!($id == $departmentIdRequester)) {
//                print_r("restriction!");
                return false;
            }
            return true;
        }
        return true;
    }
}
