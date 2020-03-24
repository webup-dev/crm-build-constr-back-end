<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\CreateCustomerFile;
use App\Http\Requests\CreateUserCustomer;
use App\Http\Requests\EditUserCustomer;
use App\Http\Requests\UpdateCustomerFile;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserCustomer;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group User Customers
 */
class UserCustomersController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('user_customer')->only(['index', 'show', 'store', 'update', 'softDestroy']);
        $this->middleware('platform.admin')->only(['indexSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get all user-customers
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User-Customers are retrieved successfully.",
     *  "data": [{
     *      "id": 1,
     *      "user_id": 16,
     *      "customer_id": 1,
     *      "deleted_at": null,
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object",
     *      "customer": "object"
     *    },
     *    {
     *      "id": 2,
     *      "user_id": 17,
     *      "customer_id": 2,
     *      "deleted_at": null,
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object",
     *      "customer": "object"
     *    }]
     * }
     *
     * @response 204 {
     *  "message": "No content."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $userCustomers = UserCustomer::with(['user', 'customer'])
            ->get();

        if ($userCustomers->count() === 0) {
            $response = [
                'success' => true,
                'code'    => 204,
                'message' => "Content is empty",
                'data'    => null
            ];

            return response()->json($response, 204);
        }

        $data = $userCustomers->toArray();

        $data = $this->filter($data);

        if (count($data) === 0) {
            $response = [
                'success' => true,
                'code'    => 204,
                'message' => "Content is empty",
                'data'    => null
            ];

            return response()->json($response, 204);
        }

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "User-Customers are retrieved successfully.",
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    private function filter($data)
    {
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(['developer', 'platform-superadmin', 'platform-admin'], $roleNamesArr)) {
            return $data;
        }

        if (oneFromArrInOtherArr([
            'organization-superadmin',
            'organization-admin',
            'organization-general-manager',
            'organization-sales-manager',
            'organization-production-manager',
            'organization-administrative-leader',
            'organization-estimator',
            'organization-project-manager',
            'organization-administrative-assistant'
        ], $roleNamesArr)) {
            $departmentId = $user->user_profile->department_id;

            $res      = [];
            $elements = Organization::all()->toArray();
            foreach ($data as $item) {
                if (isOwn($elements, $departmentId, $item['customer']['organization_id'])) {
                    $res[] = $item;
                }
            }

            return $res;
        } else {
            return null;
        }
    }

    /**
     * Get all SoftDeleted user-customers
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Soft-deleted User-Customers are retrieved successfully.",
     *  "data": [{
     *      "id": 1,
     *      "user_id": 16,
     *      "customer_id": 1,
     *      "deleted_at": "2019-06-25 07:12:03",
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object",
     *      "customer": "object"
     *    },
     *    {
     *      "id": 2,
     *      "user_id": 17,
     *      "customer_id": 2,
     *      "deleted_at": "2019-06-25 07:12:03",
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object",
     *      "customer": "object"
     *    }]
     * }
     *
     * @response 204 {
     *  "message": "No content."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @return Response
     */
    public function indexSoftDeleted()
    {
        $userCustomer = UserCustomer::with(['user', 'customer'])
            ->onlyTrashed()
            ->get();

        if ($userCustomer->count() === 0) {
            $response = [
                'success' => true,
                'code'    => 204,
                'message' => "Content is empty",
                'data'    => null
            ];

            return response()->json($response, 204);
        }

        $data = $userCustomer->toArray();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "Soft-deleted User-Customers are retrieved successfully.",
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    /**
     * Get specific User-Customer
     *
     * @bodyParam id integer required UserCustomer ID
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - for customers of his organization
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User-customer is retrieved successfully.",
     *  "data": {
     *      "id": 1,
     *      "user_id": 16,
     *      "customer_id": 1,
     *      "deleted_at": "2019-06-25 07:12:03",
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object",
     *      "customer": "object"
     *    }
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
     *  "message": "Incorrect Entity ID.",
     *  "data": null
     * }
     *
     * @response 500 {
     *  "message": "Could not get UserCustomer."
     * }
     *
     * @param $id
     * @return Response
     */
    public
    function show($id)
    {
        $userCustomer = UserCustomer::whereId($id)
            ->with(['user', 'customer'])
            ->first();

        if (!$userCustomer) {
            $response = [
                "success" => false,
                "code"    => 456,
                "message" => "Incorrect Entity ID.",
                "data"    => null
            ];

            return response()->json($response, 456);
        }

        $user        = Auth::guard()->user();
        $userProfile = $user->user_profile;
        $customer    = Customer::whereId($userCustomer->customer_id)->first();

        $organizations = Organization::all()->toArray();

        if (!isOwn($organizations, $userProfile->department_id, $customer->organization_id)) {
            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => 'User-Customer is retrieved successfully.',
            'data'    => $userCustomer
        ];

        return response()->json($response, 200);
    }

    private function createUser($email)
    {
        $rules = array(
            'email' => 'required|email'
        );

        $messages = array(
            'email.required' => 'Email is required.',
            'email.email'    => 'Must be Email.'
        );

        $validator = Validator::make(['email' => $email], $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }
        $user           = new User();
        $user->email    = $email;
        $array          = explode('@', $email);
        $trans          = array("." => "-");
        $str            = strtr($array[0], $trans);
        $user->name     = 'Customer ' . $str;
        $user->password = bcrypt('12345678');
        $user->save();

        return $user->id;
    }

    /**
     * Create User-Customer
     *
     * @bodyParam user_id integer required User ID
     * @bodyParam customer_id integer required Customer ID
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - for customers of his organization
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User-customer is created successfully.",
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
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect entity ID.",
     *  "data": null
     * }
     *
     * @response 457 {
     *  "success": false,
     *  "code": 457,
     *  "message": "A try to double the item.",
     *  "data": null
     * }
     *
     * @response 500 {
     *  "message": "Could not create Customer's file."
     * }
     *
     * @param CreateUserCustomer $request
     * @return void
     */
    public
    function store(CreateUserCustomer $request)
    {
        $data = $request->all();

        // Check is customer_id is available
        $customer = Customer::whereId($data['customer_id'])->first();

        if (!$customer) {
            $response = [
                "success" => false,
                "code"    => 456,
                "message" => "Incorrect Entity ID.",
                "data"    => null
            ];

            return response()->json($response, 456);
        }

        $user        = Auth::guard()->user();
        $userProfile = $user->user_profile;

        $organizations = Organization::all()->toArray();

        if (!isOwn($organizations, $userProfile->department_id, $customer->organization_id)) {
            $response = [
                'success' => false,
                'message' => 'Permission to department is absent.'
            ];

            return response()->json($response, 454);
        }

        // Check does email exist
        $userFromData = User::whereEmail($data['email'])->first();

        if (!$userFromData) {
            // create user and get its id
            $userId = $this->createUser($data['email']);
        } else {
            $userId = $userFromData->id;
        }

        // check is this user bond to Customer?
        $userCustomer = UserCustomer::where('user_id', $userId)
            ->where('customer_id', $data['customer_id'])
            ->first();

        if (!$userCustomer) {
            $userCustomer = new UserCustomer([
                'customer_id' => $data['customer_id'],
                'user_id'     => $userId
            ]);

            if ($userCustomer->save()) {
                $response = [
                    'success' => true,
                    'code'    => 200,
                    'message' => 'User-Customer is created successfully.',
                    'data'    => null
                ];
                return response()->json($response, 200);
            } else {
                return $this->response->error("Could not create User-Customer", 500);
            }
        }

        $response = [
            "success" => false,
            "code"    => 457,
            "message" => "A try to double the item.",
            "data"    => null
        ];
        return response()->json($response, 457);
    }

    /**
     * Edit data of the specified User-Customer
     *
     * @bodyParam user_id integer required User ID
     * @bodyParam customer_id integer required Customer ID
     *
     * @queryParam id int required User-Customer ID
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's file is updated successfully.",
     *  "data": {
     *      "id": 1,
     *      "user_id": 16,
     *      "customer_id": 1,
     *      "deleted_at": "2019-06-25 07:12:03",
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object",
     *      "customer": "object"
     *    }
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
     *  "message": "Permission is absent by the Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 456 {
     *  "success": true,
     *  "code": 456,
     *  "message": "Incorrect Entity ID.",
     *  "data": null
     * }
     *
     * @param EditUserCustomer $request
     * @param $id
     * @return void
     */
    public
    function update(EditUserCustomer $request, $id)
    {
        $userCustomer = UserCustomer::whereId($id)->first();

        if (!$userCustomer) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect Entity ID.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $data = $request->all();

        $user     = User::whereId($data['user_id'])->first();
        $customer = Customer::whereId($data['customer_id'])->first();
        if (!$user or !$customer) {
            $response = [
                'success' => false,
                'code'    => 422,
                'message' => 'The given data was invalid.',
                'data'    => null
            ];

            return response()->json($response, 422);
        }

        $userCustomer->user_id     = $data['user_id'];
        $userCustomer->customer_id = $data['customer_id'];

        if ($userCustomer->save()) {
            $userCustomer = UserCustomer::whereId($id)
                ->with(['user', 'customer'])
                ->first();

            $response = [
                'success' => true,
                'code'    => 200,
                'data'    => $userCustomer,
                'message' => "User-Customer is updated successfully."
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error("Could not update User-Customer", 500);
        }
    }

    /**
     * Soft Delete User-Customer
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *
     * @queryParam id int required User-Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User-Customer is soft-deleted successfully.",
     *  "data": null
     * }
     *
     * @response 456 {
     *  "success": true,
     *  "code": 456,
     *  "message": "Incorrect entity ID.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @param $id
     * @return void
     * @throws \Exception
     */
    public
    function softDestroy($id)
    {
        $userCustomer = UserCustomer::whereId($id)->first();

        if (!$userCustomer) {
            $response = [
                "success" => false,
                "code"    => 456,
                "message" => "Incorrect entity ID.",
                "data"    => null
            ];

            return response()->json($response, 456);
        }

        if ($userCustomer->delete()) {
            $response = [
                "success" => true,
                "code"    => 200,
                "message" => "User-Customer is soft-deleted successfully.",
                "data"    => null
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error("Could not delete User-Customer.", 500);
        }
    }

    /**
     * Restore specific User-Customer
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required User-Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's file is restored successfully.",
     *  "data": null
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
     * @param $id
     * @return void
     */
    public function restore($id)
    {
        $userCustomer = UserCustomer::onlyTrashed()->whereId($id)->first();

        if (!$userCustomer) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        // Restore customer's comment
        $userCustomer->restore();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => 'User-Customer is restored successfully.',
            'data'    => null
        ];

        return response()->json($response, 200);
    }

    /**
     * Destroy specific User-Customer permanently
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required User-Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User-Customer is deleted permanently.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
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
        $userCustomer = UserCustomer::onlyTrashed()
            ->whereId($id)
            ->first();

        if (!$userCustomer) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        // Restore customer's comment
        $userCustomer->forceDelete();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "User-Customer is destroyed permanently.",
            'data'    => null
        ];

        return response()->json($response, 200);
    }
}

