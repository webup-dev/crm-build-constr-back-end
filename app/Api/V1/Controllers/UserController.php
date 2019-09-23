<?php

namespace App\Api\V1\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;

/**
 * @group Users
 */
class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Get the authenticated User
     *
     * @response 200 {
     *  "id": 1,
     *  "name": "Super User",
     *  "email": "superuser@admin.com",
     *  "created_at": "2019-12-08 13:25:36",
     *  "updated_at": "2019-12-08 13:25:36"
     * }
     *
     * @response 401 {
     *  "error": {
     *      "message": "The token has been blacklisted",
     *      "status_code": 401
     *    }
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard()->user());
    }

    /**
     * Delete user
     *
     * @response 200 {
     *  "success": true,
     *  "message": "User deleted successfully."
     * }
     *
     * @response 500 {
     *  "success": false,
     *  "message": "Can not get User."
     * }
     *
     * @response 500 {
     *  "success": false,
     *  "message": "User did not delete."
     * }
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($userId)
    {
        $user = User::whereId($userId)->first();
        if (!$user) {
            $response = [
                'success' => false,
                'message' => 'Can not get User.'
            ];

            return response()->json($response, 500);
        }

        if (!($user->delete())) {
            $response = [
                'success' => false,
                'message' => 'User did not delete.'
            ];

            return response()->json($response, 500);
        }

        $response = [
            'success' => true,
            'message' => 'User deleted successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Get index of user-roles
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "user_id": 1,
     *    "role_id": 1,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "user_id": 1,
     *    "role_id": 2,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "User-roles retrieved successfully"
     * }
     * @response 404 {
     *  "message": "User-roles not found."
     * }
     *
     * @return Response
     */
    public function userRolesIndex()
    {
        $user_roles = User_role::all();

        if ($user_roles->count() === 0) {
            $response = [
                'success' => false,
                'message' => 'User-roles not found.'
            ];

            return response()->json($response, 404);
        }

        $data = $user_roles->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Roles retrieved successfully'
        ];

        return response()->json($response, 200);
    }

    /**
     * Get index of user-roles for specified user
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "role_id": 1,
     *    "name": "superadmin"
     *   },
     *   {
     *    "role_id": 2,
     *    "name": "admin"
     *   }],
     *  "message": "User-roles retrieved successfully"
     * }
     * @response 422 {
     *  "message": "User does not exist."
     * }
     * @response 422 {
     *  "message": "User-Roles do not exist."
     * }
     *
     * @param int $id
     * @return void
     */
    public function specifiedUserRolesIndex($id)
    {
        $user = User::whereId($id)->get();
        if ($user->count() === 0) {
            $response = [
                'success' => false,
                'message' => "User does not exist."
            ];

            return response()->json($response, 422);
        }

        $user_roles = User_role::select('role_id')->where('user_id', $id)->get();

        if ($user_roles->count() === 0) {
            $response = [
                'success' => false,
                'message' => "User-Roles do not exist."
            ];

            return response()->json($response, 422);
        }

        $roles = Role::all()->keyBy('id');

        $user_roles = $this->formData2($user_roles, $roles);

        $data = $user_roles->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'User-Roles retrieved successfully'
        ];

        return response()->json($response, 200);
    }

    /**
     * Form User-Roles collection for response
     *
     * @param $user_roles
     * @param $roles
     * @return
     */
    private function formData2($user_roles, $roles)
    {
        // add name
        foreach ($user_roles as $item) {
            $roleId = $item->role_id;
            $item->name = $roles[$roleId]->name;
            $item->id = $roleId;
        }

        return $user_roles;
    }

    /**
     * Get index of user-roles with full data
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "user_id": 1,
     *    "user_name": "Joe Dow",
     *    "role_ids": [1, 2],
     *    "role_names": "superadmin, admin",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "user_id": 2,
     *    "user_name": "Jon Pace",
     *    "role_ids": [1, 2],
     *    "role_names": "superadmin, admin",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Data is formed successfully."
     * }
     * @response 422 {
     *  "message": "Users do not exist."
     * }
     * @response 422 {
     *  "message": "Roles do not exist."
     * }
     * @response 422 {
     *  "message": "User-Roles do not exist."
     * }
     *
     * @return Response
     */
    public function userRolesIndexFull()
    {
        $users = User::all();
        if (count($users) === 0) {
            $response = [
                'success' => false,
                'message' => 'Users do not exist.'
            ];

            return response()->json($response, 422);
        }

        $roles = Role::all()->keyBy('id');
        if (count($roles) === 0) {
            $response = [
                'success' => false,
                'message' => 'Roles do not exist.'
            ];

            return response()->json($response, 422);
        }

        $user_roles = User_role::all()->groupBy('user_id')->toArray();

        if (count($user_roles) === 0) {
            $response = [
                'success' => false,
                'message' => 'User-Roles do not exist.'
            ];

            return response()->json($response, 422);
        }

        $user_roles_formatted = $this->pluckRoleId($user_roles);

        $dataFormatted = $this->formData($users, $roles, $user_roles_formatted);

        $response = [
            'success' => true,
            'data'    => $dataFormatted,
            'message' => 'Data is formed successfully.'
        ];

        return response()->json($response, 200);
    }

    private function formData($users, $roles, $user_roles_formatted)
    {
        foreach ($users as $user) {
            if (!isset($user_roles_formatted[$user->id])) {
                $user->role_ids   = [];
                $user->role_names = "";
                continue;
            }

            $user_rolesTemp = $user_roles_formatted[$user->id];
            $user->role_ids = json_encode($user_rolesTemp);

            $names = "";
            $l     = count($user_rolesTemp);
            $i     = 0;
            foreach ($user_rolesTemp as $id) {
                $names .= $roles[$id]->name;

                if ($i < $l - 1) {
                    $names .= ", ";
                }
                $i++;
            }

            $user->role_names = $names;
        }

        return $users->toArray();
    }

    private function pluckRoleId($userRoles)
    {
        $arrayResult = [];
        foreach ($userRoles as $key1 => $arrayInternal) {
            $arrayResult[$key1] = [];
            foreach ($arrayInternal as $key2 => $item) {
                $arrayResult[$key1][] = $item['role_id'];
            }
            $arrayResult[$key1] = array_unique($arrayResult[$key1]);
        }

        return $arrayResult;
    }

    /**
     * Store a newly created Roles for User in storage.
     *
     * @bodyParam user_id int required User ID
     * @bodyParam role_ids array required [['id'=>1],['id'=>2]], id is role ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "New Roles for User created successfully."
     * }
     *
     * @response 406 {
     *  "success": false,
     *  "message": "Creating is impossible. User has roles already."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "Creating is impossible. User does not exist."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Roles for User",
     *      "status_code": 500
     *    }
     * }
     *
     * @param Request $request
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function userRolesStore(Request $request, $id)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'role_ids' => 'required',
        );

        $messages = array(
            'user_id.required'   => 'Please select a user.',
            'role_ids.required'   => 'Please select roles.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }

        $user_id = $request->get('user_id');
        $user    = User::find($user_id);

        // Check does user exist?
        if (!$user) {
            $response = [
                'success' => false,
                'message' => "Creating is impossible. User does not exist."
            ];
            return response()->json($response, 422);
        }

        // Check does user have roles already?
        $roles      = $user->roles()->get();
        $rolesCount = $roles->count();
        if ($rolesCount != 0) {
            $response = [
                'success' => false,
                'message' => "Creating is impossible. User has roles already."
            ];
            return response()->json($response, 406);
        }

        $roleIds = $request->get('role_ids'); //array

        $role_ids_arr = [];
        foreach ($roleIds as $item) {
            $role_ids_arr[] = $item['id'];
        }

        $user->roles()->attach($role_ids_arr);

        $roles      = $user->roles()->get();
        $rolesCount = $roles->count();

        if ($rolesCount != 0) {
            $response = [
                'success' => true,
                'message' => 'New Roles for User created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create roles', 500);
        }
    }

    /**
     * Update the roles of the user in storage.
     *
     * @bodyParam user_id int required User ID
     * @bodyParam role_ids array required [['role_id'=>1],['role_id'=>2]], id is role ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Roles for User are updated successfully."
     * }
     *
     * @response 406 {
     *  "success": false,
     *  "message": "Updating is impossible. User does not have roles yet."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "Updating is impossible. User does not exist."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Roles of User",
     *      "status_code": 500
     *    }
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function userRolesUpdate(Request $request, $id)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'role_ids' => 'required',
        );

        $messages = array(
            'user_id.required'   => 'Please select a user.',
            'role_ids.required'   => 'Please select roles.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }

        $user_id = $request->get('user_id');
        $user    = User::find($user_id);

        // Check does user exist?
        if (!$user) {
            $response = [
                'success' => false,
                'message' => "Updating is impossible. User does not exist."
            ];
            return response()->json($response, 422);
        }

        // Check does user have roles already?
        $roles = $user->roles()->get();
        if ($roles->count() === 0) {
            $response = [
                'success' => false,
                'message' => "Updating is impossible. User does not have roles yet."
            ];
            return response()->json($response, 406);
        }

        $roleIds = $request->get('role_ids');
        $role_ids_arr = [];
        foreach ($roleIds as $item) {
            $role_ids_arr[] = $item['id'];
        }

        $user->roles()->sync($role_ids_arr);

        $roles      = $user->roles()->get();
        $rolesCount = $roles->count();

        if ($rolesCount != 0) {
            $response = [
                'success' => true,
                'message' => 'Roles for User are updated successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update roles', 500);
        }
    }

    /**
     * Remove the roles of the user from storage.
     *
     * @response 200 {
     *  "success": true,
     *  "message": "User Roles are deleted successfully."
     * }
     *
     * @response 406 {
     *  "success": false,
     *  "message": "It is impossible to delete Roles. User does not have roles."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "It is impossible to delete Roles. User does not exist."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not delete User Roles.",
     *      "status_code": 500
     *    }
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function userRolesDestroy($id)
    {
        $user = User::find($id);

        // Check does user exist?
        if (!$user) {
            $response = [
                'success' => false,
                'message' => "It is impossible to delete Roles. User does not exist."
            ];
            return response()->json($response, 422);
        }

        // Check does user have roles already?
        $roles = $user->roles()->get();
        if ($roles->count() === 0) {
            $response = [
                'success' => false,
                'message' => "It is impossible to delete Roles. User does not have roles."
            ];
            return response()->json($response, 406);
        }

        if ($user->roles()->detach()) {
            $response = [
                'success' => true,
                'message' => "User Roles are deleted successfully."
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not delete User Roles.', 500);
        }
    }
}
