<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\UpdateUserProfile;
use App\Models\Activity;
use App\Models\User_role;
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
 * @group User Profiles
 */
class UserProfilesController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('user_profiles_organization.admin')->only(['index', 'indexSoftDeleted', 'store', 'softDestroy', 'restore', 'destroyPermanently']);
        $this->middleware('user_profiles_organization.admin.own')->only(['show', 'update']);
        $this->middleware('activity');
    }

    /**
     * Get index of user profiles
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
     *  "data": [{
     *    "id": 1,
     *    "user_id": 1,
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "department_id": 1,
     *    "organization": "object",
     *    "status": "active",
     *    "start_date": null,
     *    "termination_date": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "user_id": 2,
     *    "first_name": "Chloe",
     *    "last_name": "Tariakis",
     *    "department_id": 1,
     *    "organization": "object",
     *    "status": "active",
     *    "start_date": null,
     *    "termination_date": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "User Profiles are retrieved successfully."
     * }
     *
     * @response 453 {
     *  "success": true,
     *  "message": "User Profiles do not exist."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "You do not have permissions."
     * }
     *
     * @return Response
     */
    public function index()
    {
//        dd("here");
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();
        if (oneFromArrInOtherArr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
            $user         = User::find($user->id);
            $userProfile  = $user->user_profile;
            $departmentId = $userProfile->department_id;
            $userProfiles = User_profile::with('organization')
                ->select('id', 'user_id', 'first_name', 'last_name', 'department_id', 'status', 'start_date', 'termination_date', 'created_at', 'updated_at')
                ->where('department_id', $departmentId)
                ->get();
        } else {
            $userProfiles = User_profile::with('organization')
                ->select('id', 'user_id', 'first_name', 'last_name', 'department_id', 'status', 'start_date', 'termination_date', 'created_at', 'updated_at')
                ->get();
        }

        $data = $userProfiles->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => "User Profiles are retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    /**
     * Get index of soft-deleted user profiles
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "user_id": 1,
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "department_id": 1,
     *    "organization": "object",
     *    "status": "active",
     *    "start_date": null,
     *    "termination_date": null,
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "user_id": 2,
     *    "first_name": "Chloe",
     *    "last_name": "Tariakis",
     *    "department_id": 1,
     *    "organization": "object",
     *    "status": "active",
     *    "start_date": null,
     *    "termination_date": null,
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "User Profiles are retrieved successfully."
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
     * Get data of the specified user profile
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
     *  "data": {
     *    "id": 1,
     *    "user_id": 1,
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "title": null,
     *    "department_id": 1,
     *    "organization": "Organization object",
     *    "phone_home": "(999) 999-9999",
     *    "phone_work": "(999) 999-9999",
     *    "phone_extension": "99999",
     *    "phone_mob": "(999) 999-9999",
     *    "email_personal": "joe.dassen@gmail.com",
     *    "email_work": "joe.dassen@admin.com",
     *    "address_line_1": "av. Strawberry 45",
     *    "address_line_2": null,
     *    "city": "New York",
     *    "state": "NY",
     *    "zip": "02633",
     *    "status": "active",
     *    "start_date": null,
     *    "termination_date": null,
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *  "message": "User Profile is retrieved successfully."
     * }
     *
     * @response 452 {
     *  "success": true,
     *  "message": "User Profile does not exist."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "You do not have access."
     * }
     *
     * @param $id
     * @return void
     */
    public
    function show($id)
    {
//        $checkOrganization = $this->checkUserFromOrganization($id);
//        dd("here");
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();
        $userProfile  = User_profile::with('organization')
            ->whereId($id)
            ->first();

        if (!$userProfile) {
            $response = [
                'success' => false,
                'message' => "User Profile does not exist."
            ];

            return response()->json($response, 452);
        }

//        if (oneFromArrInOtherArr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
//            $user                  = User::find($user->id);
//            $userProfileRequester  = $user->user_profile;
//            $departmentIdRequester = $userProfileRequester->department_id;
//
//            if (!($userProfile->department_id == $departmentIdRequester)) {
//                $response = [
//                    'success' => false,
//                    'message' => "You do not have access."
//                ];
//
//                return response()->json($response, 453);
//            }
//        }

        $data = $userProfile->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => "User Profile is retrieved successfully."
        ];

        return response()->json($response, 200);
    }

    /**
     * Create user, user profile
     *
     * @bodyParam first_name string required User First Name
     * @bodyParam last_name string required User Last Name
     * @bodyParam title string Title
     * @bodyParam department_id int required User Organization ID
     * @bodyParam organization object required Organization object
     * @bodyParam phone_home string Home Phone
     * @bodyParam phone_work string Work Phone
     * @bodyParam phone_extension string Phone Extension
     * @bodyParam phone_mob string Mobile Phone
     * @bodyParam email_personal string Email Personal
     * @bodyParam email_work string Email Work
     * @bodyParam address_line_1 string required Address Line 1
     * @bodyParam address_line_2 string Address Line 2
     * @bodyParam city string required City
     * @bodyParam state string required State Code
     * @bodyParam zip string required ZIP
     * @bodyParam status string required User Ststus
     * @bodyParam start_date  string Start Date of Work
     * @bodyParam termination_date string Termination Date of Work
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
     *  "message": "User and User Profile are created successfully."
     * }
     *
     * @response 422 {
     *  "error": {
     *    "message": "User and User Profile are created successfully.",
     *    "errors": []
     *   }
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent."
     * }
     *
     * @param StoreUserProfile $request
     * @return Response
     */
    public
    function store(StoreUserProfile $request)
    {
        $data            = $request->all();
        $data['user_id'] = $this->createUser($data);

        $userProfile = new User_profile();
        $userProfile->fill($data);

        if ($userProfile->save()) {
            $response = [
                'success' => true,
                'message' => 'User and User Profile are created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create User, User Profile', 500);
        }
    }

    private
    function createUser($data)
    {
        $user = new User([
            'password' => bcrypt('12345678'),
            'name'     => $data['first_name'] . ' ' . $data['last_name'],
            'email'    => $data['email_work']
        ]);

        if (!$user->save()) {
            throw new HttpException(500);
        }

        return $user->id;
    }

    /**
     * Edit data of the specified user profile
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
     * @bodyParam user_id int required User Profile ID
     * @bodyParam first_name string required User First Name
     * @bodyParam last_name string required User Last Name
     * @bodyParam title string Title
     * @bodyParam department_id int required User Organization ID
     * @bodyParam phone_home string Home Phone
     * @bodyParam phone_work string Work Phone
     * @bodyParam phone_extension string Phone Extension
     * @bodyParam phone_mob string Mobile Phone
     * @bodyParam email_personal string Email Personal
     * @bodyParam email_work string Email Work
     * @bodyParam address_line_1 string required Address Line 1
     * @bodyParam address_line_2 string Address Line 2
     * @bodyParam city string required City
     * @bodyParam state string required State Code
     * @bodyParam zip string required ZIP
     * @bodyParam status string required User Ststus
     * @bodyParam start_date  string Start Date of Work
     * @bodyParam termination_date string Termination Date of Work
     * @bodyParam deleted_at timestamp DateTime of soft deleting
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *    "id": 1,
     *    "user_id": 1,
     *    "first_name": "Joe",
     *    "last_name": "Dassen",
     *    "title": null,
     *    "department_id": 1,
     *    "department": "Organization object",
     *    "phone_home": "(999) 999-9999",
     *    "phone_work": "(999) 999-9999",
     *    "phone_extension": "99999",
     *    "phone_mob": "(999) 999-9999",
     *    "email_personal": "joe.dassen@gmail.com",
     *    "email_work": "joe.dassen@admin.com",
     *    "address_line_1": "av. Strawberry 45",
     *    "address_line_2": null,
     *    "city": "New York",
     *    "state": "NY",
     *    "zip": "02633",
     *    "status": "active",
     *    "start_date": null,
     *    "termination_date": null,
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *  "message": "User Profile is updated successfully."
     * }
     *
     * @response 453 {
     *  "success": true,
     *  "message": "User Profile does not exist."
     * }
     *
     * @param StoreUserProfile $request
     * @param $id
     * @return void
     */
    public
    function update(UpdateUserProfile $request, $id)
    {
        $this->checkUserFromOrganization($id);

        $userProfile = User_profile::whereId($id)->first();

        if (!$userProfile) {
            $response = [
                'success' => false,
                'message' => 'User Profile does not exist.'
            ];

            return response()->json($response, 452);
        }

        $userProfile->fill($request->all());

        $data = json_encode($userProfile);

        if ($userProfile->save()) {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'User Profile is updated successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update Role', 500);
        }
    }

    /**
     * Soft Delete user profile
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
     * @queryParam id int required User Profile ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "User Profile is soft-deleted successfully."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "User Profile is absent."
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
//        dd("here controller");
        $userProfile = User_profile::whereId($id)->first();
        if (!$userProfile) {
            $response = [
                'success' => false,
                'message' => 'User Profile is absent.'
            ];

            return response()->json($response, 422);
        }
        $user = User::whereId($userProfile->user_id)->first();

        // there are 3 DB tables that are bond to User: activities, user_roles, user_profiles
        Activity::truncate();

        $userRoles = User_role::whereUserId($user->id)->get();
        foreach ($userRoles as $userRole) {
            $userRole->delete();
        }

        $userProfile->delete();
        $user->delete();

        $response = [
            'success' => true,
            'message' => 'User is soft-deleted successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Restore user profile
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
     * @queryParam id int required User Profile ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "User Profile is restored successfully."
     * }
     *
     * @response 452 {
     *  "success": false,
     *  "message": "User Profile is absent."
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
    public function restore($id)
    {
        $userProfile = User_profile::onlyTrashed()->whereId($id)->first();

        if (!$userProfile) {
            $response = [
                'success' => false,
                'message' => 'User Profile is absent.'
            ];

            return response()->json($response, 422);
        }

        // Restore user
        $userId = $userProfile->user_id;
        $user   = User::onlyTrashed()->whereId($userId)->first();
        $user->restore();

        // Restore user profile
        $userProfile->restore();

        // Restore user's roles
        $userRoles = User_role::onlyTrashed()->whereUserId($userId)->get();
        foreach ($userRoles as $userRole) {
            $userRole->restore();
        }

        $response = [
            'success' => true,
            'message' => 'User is restored successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Destroy user profile permanently
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
     * @queryParam id int required User Profile ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "User Profile is deleted permanently."
     * }
     *
     * @response 452 {
     *  "success": false,
     *  "message": "User Profile is absent."
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
        $checkOrganization = $this->checkUserFromOrganization($id);

        $userProfile = User_profile::withTrashed()->whereId($id)->first();

        if (!$userProfile) {
            $response = [
                'success' => false,
                'message' => 'User Profile is absent.'
            ];

            return response()->json($response, 422);
        }

        $user      = User::withTrashed()->whereId($userProfile->user_id)->first();
        $userRoles = User_role::withTrashed()->whereUserId($userProfile->user_id)->get();
        $userProfile->forceDelete();
        foreach ($userRoles as $userRole) {
            $userRole->forceDelete();
        }
        if ($user) {
            $user->forceDelete();
        }

        $response = [
            'success' => true,
            'message' => 'User is deleted permanently.'
        ];

        return response()->json($response, 200);

    }

    private
    function checkUserFromOrganization($id)
    {
        // check 'organization-superadmin', 'organization-admin'
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        if (oneFromArrInOtherArr(['organization-superadmin', 'organization-admin'], $roleNamesArr)) {
            $user                  = User::find($user->id);
            $userProfileRequester  = $user->user_profile;
            $departmentIdRequester = $userProfileRequester->department_id;

            if (!($id == $departmentIdRequester)) {
                $response = [
                    'success' => false,
                    'message' => "You do not have access."
                ];

                return response()->json($response, 453);
            }
            return true;
        }
        return true;
    }
}
