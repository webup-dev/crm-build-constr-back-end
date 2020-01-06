<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\StoreUserDetails;
use App\Http\Requests\UpdateUserDetails;
use App\Http\Requests\UpdateUserProfile;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\User_role;
use App\Models\UserCustomer;
use App\Models\UserDetail;
use Config;
use App\Api\V1\Requests\SignUpRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserProfile;
use App\Models\Role;
use App\Models\User;
use App\Models\User_profile;
use Auth;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @group User Details
 */
class UserDetailsController extends Controller
{
    use Helpers;

    public function __construct()
    {
        /**
         * Permissions
         * indexSoftDeleted, restore, deletePermanently: platform level
         * show, store, update: organization level + customer for own users
         * index, softDelete: organization admin
         */
        $this->middleware('user_details.customers_and_organization_users')->only(['store', 'show', 'update']);
        $this->middleware('user_details.organization_admin')->only(['index', 'softDestroy']);
        $this->middleware('platform.admin')->only(['indexSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get index of user details
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *   conditional access:
     *     organization-users - to users of his organization
     *     customer - to own account
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User Details are retrieved successfully.",
     *  "data": [{
     *    "id": 1,
     *    "user_id": 1,
     *    "prefix": "Mr",
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "suffix": "Esq",
     *    "work_title": "Dassen Corp",
     *    "work_department": "Central Office",
     *    "work_role": "CEO",
     *    "phone_home": "0123456789",
     *    "phone_work": "0123456789",
     *    "phone_extension": "123",
     *    "phone_mob": "0123456789",
     *    "phone_fax": "0123456789",
     *    "email_work": "Dassen@dassen.com",
     *    "email_personal": "Dassen@@gmail.com",
     *    "line_1": "123 Main Road",
     *    "line_2": "app 1",
     *    "city": "Dassenbourg",
     *    "state": "MI",
     *    "zip": "12345",
     *    "status": "active",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 1,
     *    "user_id": 1,
     *    "prefix": "Mr",
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "suffix": "Esq",
     *    "work_title": "Dassen Corp",
     *    "work_department": "Central Office",
     *    "work_role": "CEO",
     *    "phone_home": "0123456789",
     *    "phone_work": "0123456789",
     *    "phone_extension": "123",
     *    "phone_mob": "0123456789",
     *    "phone_fax": "0123456789",
     *    "email_work": "Dassen@dassen.com",
     *    "email_personal": "Dassen@@gmail.com",
     *    "line_1": "123 Main Road",
     *    "line_2": "app 1",
     *    "city": "Dassenbourg",
     *    "state": "MI",
     *    "zip": "12345",
     *    "status": "active",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }]
     * }
     *
     * @response 204 {
     *  "message": "Empty content."
     * }
     *
     * @response 453 {
     *  "success": true,
     *  "message": "User Profiles do not exist."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();
        if (one_from_arr_in_other_arr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
            $user          = User::find($user->id);
            $userProfile   = $user->user_profile;
            $departmentId  = $userProfile->department_id;
            $organizations = Organization::all()->toArray();

            // get all nested organization_id
            $organizationIds = collectIds($organizations, $departmentId);

            // get all own customers
            $customerIds = Customer::whereIn('organization_id', $organizationIds)
                ->get()
                ->pluck('id')
                ->all();

            // get all own users
            $userIds = UserCustomer::whereIn('customer_id', $customerIds)
                ->get()
                ->pluck('user_id')
                ->all();
            // get all user_details
            $userDetails = UserDetail::select('id', 'user_id', 'first_name', 'last_name', 'status', 'created_at', 'updated_at')
                ->whereIn('user_id', $userIds)
                ->get();
        } else {
            $userDetails = UserDetail::select('id', 'user_id', 'first_name', 'last_name', 'status', 'created_at', 'updated_at')
                ->get();
        }

        if ($userDetails->count() === 0) {
            $response = [
                'success' => true,
                'code'    => 204,
                'message' => "User Details are retrieved successfully.",
                'data'    => null
            ];

            return response()->json($response, 204);
        }

        $data = $userDetails->toArray();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "User Details are retrieved successfully.",
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    /**
     * Get index of soft-deleted user details
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User Details are retrieved successfully.",
     *  "data": [{
     *    "id": 1,
     *    "user_id": 1,
     *    "prefix": "Mr",
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "suffix": "Esq",
     *    "work_title": "Dassen Corp",
     *    "work_department": "Central Office",
     *    "work_role": "CEO",
     *    "phone_home": "0123456789",
     *    "phone_work": "0123456789",
     *    "phone_extension": "123",
     *    "phone_mob": "0123456789",
     *    "phone_fax": "0123456789",
     *    "email_work": "Dassen@dassen.com",
     *    "email_personal": "Dassen@@gmail.com",
     *    "line_1": "123 Main Road",
     *    "line_2": "app 1",
     *    "city": "Dassenbourg",
     *    "state": "MI",
     *    "zip": "12345",
     *    "status": "active",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "user_id": 2,
     *    "prefix": "Mr",
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "suffix": "Esq",
     *    "work_title": "Dassen Corp",
     *    "work_department": "Central Office",
     *    "work_role": "CEO",
     *    "phone_home": "0123456789",
     *    "phone_work": "0123456789",
     *    "phone_extension": "123",
     *    "phone_mob": "0123456789",
     *    "phone_fax": "0123456789",
     *    "email_work": "Dassen@dassen.com",
     *    "email_personal": "Dassen@@gmail.com",
     *    "line_1": "123 Main Road",
     *    "line_2": "app 1",
     *    "city": "Dassenbourg",
     *    "state": "MI",
     *    "zip": "12345",
     *    "status": "active",
     *    "deleted_at": "2019-06-24 07:12:03",
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
     *  "message": "Permission is absent."
     * }
     *
     * @return Response
     */
    public function indexSoftDeleted()
    {
        $userProfiles = User_profile::onlyTrashed()
            ->with('organization')
            ->select('id', 'user_id', 'first_name', 'last_name', 'department_id', 'status', 'start_date', 'termination_date', 'deleted_at', 'created_at', 'updated_at')
            ->get();

        if (!$userProfiles->count()) {
            $response = [
                'success' => true,
                'message' => "Soft Deleted User Profiles are empty."
            ];

            return response()->json($response, 204);
        }

        $data = $userProfiles->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => "User and User Profiles are retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    /**
     * Get data of the specified user details
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *   conditional access:
     *     organization-superadmin - to users of his organization
     *     organization-admin - to users of his organization
     *     user - to own profile
     *
     * @queryParam id required User Profile ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User Details are retrieved successfully.",
     *  "data": {
     *    "id": 1,
     *    "user_id": 1,
     *    "prefix": "Mr",
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "suffix": "Esq",
     *    "work_title": "Dassen Corp",
     *    "work_department": "Central Office",
     *    "work_role": "CEO",
     *    "phone_home": "0123456789",
     *    "phone_work": "0123456789",
     *    "phone_extension": "123",
     *    "phone_mob": "0123456789",
     *    "phone_fax": "0123456789",
     *    "email_work": "Dassen@dassen.com",
     *    "email_personal": "Dassen@@gmail.com",
     *    "line_1": "123 Main Road",
     *    "line_2": "app 1",
     *    "city": "Dassenbourg",
     *    "state": "MI",
     *    "zip": "12345",
     *    "status": "active",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have access."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to the department is absent."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "message": "Incorrect the Entity ID in the URL."
     * }
     *
     * @param $id
     * @return void
     */
    public
    function show($id)
    {
        $userDetails = UserDetail::with('user')
            ->whereId($id)
            ->first();

        if (!$userDetails) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => "Incorrect the Entity ID in the URL.",
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $data = $userDetails->toArray();

        $response = [
            'success' => true,
            'code'    => 200,
            'data'    => $data,
            'message' => "User-details is retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    /**
     * Create user detail
     *
     * @bodyParam user_id int required User First Name
     * @bodyParam prefix string required Prefix
     * @bodyParam first_name string required User First Name
     * @bodyParam last_name string required User Last Name
     * @bodyParam suffix string Suffix
     * @bodyParam work_title string Work Title
     * @bodyParam work_department int required Work Department
     * @bodyParam work_role int required Work Role
     * @bodyParam phone_home string Home Phone
     * @bodyParam phone_work string Work Phone
     * @bodyParam phone_extension string Phone Extension
     * @bodyParam phone_mob string Mobile Phone
     * @bodyParam phone_fax string Mobile Fax
     * @bodyParam email_personal required string Email Personal
     * @bodyParam email_work string Email Work
     * @bodyParam line_1 string required Address Line 1
     * @bodyParam line_2 string required Address Line 2
     * @bodyParam city string required City
     * @bodyParam state string required State Code
     * @bodyParam zip string required ZIP
     * @bodyParam status string required User Status
     * @bodyParam deleted_at string Date of Soft-deleting
     * @bodyParam created_at string Date of Creating
     * @bodyParam updated_at string Date of Updating
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *   conditional access:
     *     organization-superadmin - to users of his organization
     *     organization-admin - to users of his organization
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User and User Profile are created successfully.",
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
     * @param StoreUserDetails $request
     * @return Response
     */
    public
    function store(StoreUserDetails $request)
    {
        $data = $request->all();

        $userDetails = new UserDetail();
        $userDetails->fill($data);

        if ($userDetails->save()) {
            $response = [
                'success' => true,
                'code'    => 200,
                'message' => 'Item is created successfully.',
                'data'    => null
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create User Details', 500);
        }
    }

    /**
     * Edit data of the specified user details
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *   conditional access:
     *     organization-users - to users of his organization
     *     customer - to own customer
     *
     * @bodyParam user_id int required User First Name
     * @bodyParam prefix string required Prefix
     * @bodyParam first_name string required User First Name
     * @bodyParam last_name string required User Last Name
     * @bodyParam suffix string Suffix
     * @bodyParam work_title string Work Title
     * @bodyParam work_department int required Work Department
     * @bodyParam work_role int required Work Role
     * @bodyParam phone_home string Home Phone
     * @bodyParam phone_work string Work Phone
     * @bodyParam phone_extension string Phone Extension
     * @bodyParam phone_mob string Mobile Phone
     * @bodyParam phone_fax string Mobile Fax
     * @bodyParam email_personal required string Email Personal
     * @bodyParam email_work string Email Work
     * @bodyParam line_1 string required Address Line 1
     * @bodyParam line_2 string required Address Line 2
     * @bodyParam city string required City
     * @bodyParam state string required State Code
     * @bodyParam zip string required ZIP
     * @bodyParam status string required User Status
     * @bodyParam deleted_at string Date of Soft-deleting
     * @bodyParam created_at string Date of Creating
     * @bodyParam updated_at string Date of Updating
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User Details are updated successfully.",
     *  "data": {
     *    "id": 1,
     *    "user_id": 1,
     *    "prefix": "Mr",
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "suffix": "Esq",
     *    "work_title": "Dassen Corp",
     *    "work_department": "Central Office",
     *    "work_role": "CEO",
     *    "phone_home": "0123456789",
     *    "phone_work": "0123456789",
     *    "phone_extension": "123",
     *    "phone_mob": "0123456789",
     *    "phone_fax": "0123456789",
     *    "email_work": "Dassen@dassen.com",
     *    "email_personal": "Dassen@@gmail.com",
     *    "line_1": "123 Main Road",
     *    "line_2": "app 1",
     *    "city": "Dassenbourg",
     *    "state": "MI",
     *    "zip": "12345",
     *    "status": "active",
     *    "deleted_at": Null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "The given data was invalid.",
     *  "data": null
     * }
     *
     * @response 454 {
     *  "success": true,
     *  "message": "Permission to the department is absent."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @param UpdateUserDetails $request
     * @param $id
     * @return void
     */
    public
    function update(UpdateUserDetails $request, $id)
    {
        $userDetails = UserDetail::whereId($id)->first();

        if (!$userDetails) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $userDetails->fill($request->all());

        $data = json_encode($userDetails);

        if ($userDetails->save()) {
            $response = [
                'success' => true,
                'code'    => 200,
                'message' => 'User Details are updated successfully.',
                'data'    => $data
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update UserDetails', 500);
        }
    }

    /**
     * Soft Delete user details
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *   conditional access:
     *     users of the organization-admin level- to users of his organization
     *
     * @queryParam id int required User details
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User Details are soft-deleted successfully.",
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
     *  "message": "Incorrect entity ID.",
     *  "data": null
     * }
     *
     * @param $id
     * @return void
     * @throws \Exception
     */
    public
    function softDestroy($id)
    {
        $userDetails = UserDetail::whereId($id)->first();
        if (!$userDetails) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect entity ID.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $user = User::whereId($userDetails->user_id)->first();

        // there are 3 DB tables that are bond to User: activities, user_roles, user_profiles
        Activity::truncate();

        $userDetails->delete();
        $user->delete();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => 'User-details is soft-deleted successfully.',
            'data'    => null
        ];

        return response()->json($response, 200);
    }

    /**
     * Restore user details
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *
     * @queryParam id int required User-Details ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User Details are restored successfully.",
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
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @param $id
     * @return void
     */
    public function restore($id)
    {
        $userDetails = UserDetail::onlyTrashed()->whereId($id)->first();

        if (!$userDetails) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        // Restore user-details
        $userDetails->restore();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => 'User Details are restored successfully.',
            'data'    => null
        ];

        return response()->json($response, 200);
    }

    /**
     * Destroy user details permanently
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
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
     * @param $id
     * @return void
     */
    public
    function destroyPermanently($id)
    {
        $userDetails = UserDetail::withTrashed()->whereId($id)->first();
        if (!$userDetails) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => "Incorrect the Entity ID in the URL.",
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $userDetails->forceDelete();

        $response = [
            'success' => true,
            'code' => 200,
            'message' => 'User Details are deleted permanently.',
            'data' => null
        ];

        return response()->json($response, 200);
    }
}
