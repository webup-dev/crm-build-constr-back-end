<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Action_role;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\Book;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @group Action-Roles
 */
class ActionRolesController extends Controller
{
    use Helpers;

    /**
     * Get index of action_roles
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "action": "ActionRoles.index",
     *    "role_ids": "{[1,2,3]}",
     *    "role_names": "superadmin, admin",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 1,
     *    "action": "ActionRoles.create",
     *    "role_ids": "{[1,2,3]}",
     *    "role_names": "superadmin, admin",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Action-Roles retrieved successfully"
     * }
     * @response 422 {
     *  "message": "Roles do not exist."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $action_roles = Action_Role::all();
        $roles        = Role::all();

        if ($roles->count() === 0) {
            $response = [
                'success' => false,
                'message' => 'Roles do not exist'
            ];

            return response()->json($response, 422);
        }

        $data = $this->formData($action_roles, $roles);

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Action-Roles retrieved successfully'
        ];

        return response()->json($response, 200);
    }

    private function formData($action_roles, $roles)
    {
        $rolesKeyById = $roles->keyBy('id');
        foreach ($action_roles as $action_role) {
            $rolesIds = json_decode($action_role->role_ids);
            $names    = '';
            $l        = count($rolesIds);
            $i        = 0;
            foreach ($rolesIds as $rolesId) {
                $names .= $rolesKeyById[$rolesId]->name;
                if ($i < ($l - 1)) {
                    $names .= ', ';
                }
                $i++;
            }
            $action_role->role_ids   = $rolesIds;
            $action_role->role_names = $names;
        }
        return $action_roles->toArray();
    }

    /**
     * Store a newly created Action-Roles in storage.
     *
     * @response 200 {
     *  "success": true,
     *  "message": "New Action-Roles created successfully."
     * }
     *
     * @response 422 {
     *  "message": "One or more roles do not exist",
     *  "status": false
     * }
     *
     * @response 453 {
     *  "message": "Inappropriate Role ID sent",
     *  "status": false
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Action Roles",
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
            'action'   => 'required|string',
            'role_ids' => 'string',
        );

        $messages = array(
            'action.required' => 'Please enter a name.',
            'action.string'   => 'Name must be a string.',
            'role_ids.string' => 'Role_ids must be JSON.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }

        // Check role ids
        $role_ids = json_decode($request->get('role_ids'));
        if (!$this->checkRoleIDs($role_ids)) {
            $response = [
                'success' => false,
                'message' => 'Inappropriate Role ID sent'
            ];
            return response()->json($response, 453);
        }

            $actionRoles = new Action_role();

        $actionRoles->action   = $request->get('action');
        $actionRoles->role_ids = $request->get('role_ids');

        if ($actionRoles->save()) {
            $response = [
                'success' => true,
                'message' => 'New Action-Roles created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create Action Roles', 500);
        }
    }

    private function checkRoleIDs($role_ids)
    {
        $realRolesIDs = Role::all()->pluck('id')->toArray();

        $result = true;
        foreach ($role_ids as $id) {
            if (in_array($id, $realRolesIDs)) {
                continue;
            }
            $result = false;
        }
        return $result;
    }

//    /**
//     * Get the specified Role.
//     *
//     * @response 200 {
//     *  "success": true,
//     *  "data": {
//     *       "id": 1,
//     *       "name": "Role 1",
//     *       "description": "Description 1",
//     *       "created_at": "2019-12-08 13:25:36",
//     *       "updated_at": "2019-12-08 13:25:36"
//     *     },
//     *  "message": "Role retrieved successfully."
//     * }
//     *
//     * @response 204 {
//     *    "success": false,
//     *    "data": "Empty",
//     *    "message": "Role not found."
//     * }
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        $role = Role::whereId($id)->first();
//        if (!$role) {
//            $response = [
//                'success' => false,
//                'data'    => "Empty",
//                'message' => "Role not found."
//            ];
//
//            return response()->json($response, 204);
//        }
//
//        $data = $role->toArray();
//
//        $response = [
//            'success' => true,
//            'data'    => $data,
//            'message' => 'Role retrieved successfully.'
//        ];
//
//        return response()->json($response, 200);
//    }


    /**
     * Update the specified Action-Roles in storage.
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "action": "ActionRoles.index",
     *       "role_ids": "{[1,2,3]}",
     *       "role_names": "superadmin, admin",
     *       "created_at": "2019-06-24 07:12:03",
     *       "updated_at": "2019-06-24 07:12:03"
     *     },
     *  "message": "Actions-Roles is updated successfully."
     * }
     *
     * @response 422 {
     *  ""message": "One or more roles do not exist",
     *  "status_code": 422
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::whereId($id)->first();

        if (!$role) {
            $response = [
                'success' => false,
                'message' => 'Could not find Role.'
            ];

            return response()->json($response, 204);
        }

        $role->fill($request->all());

        $data = json_encode($role);

        if ($role->save()) {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Role is updated successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update Role', 500);
        }
    }

    /**
     * Remove the specified Action-Roles from storage.
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Role is deleted successfully"
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Could not find Action."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $role = Role::whereId($id)->first();

        if (!$role) {
            $response = [
                'success' => false,
                'message' => 'Could not find Role.'
            ];

            return response()->json($response, 204);
        }

        if ($role->delete()) {
            $response = [
                'success' => true,
                'message' => 'Role is deleted successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not delete Role', 500);
        }
    }
}
