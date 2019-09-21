<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Method;
use App\Models\Method_role;
use App\Models\Role;
use App\Models\Vcontroller;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Method-Roles
 */
class MethodRolesController extends Controller
{
    use Helpers;

    /**
     * Store a newly created Method_roles in storage.
     *
     * @bodyParam method_id int required Method ID
     * @bodyParam role_id int required Role ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "New Method Roles are created successfully."
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Method does not exist"
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Role does not exist"
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Method_role",
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
            'method_id' => 'required|int',
            'role_id'   => 'required|int'
        );

        $messages = array(
            'method_id.required' => 'Please, select a method.',
            'role_id.required'   => 'Please select a role.',
        );

//        $validator = Validator::make($request->all(), $rules, $messages);
//        if ($validator->fails()) {
//            $messages = $validator->messages();
//            $errors   = $messages->all();
//            return $errors;
//        }

        $methodIds = Method::all()->pluck('id')->toArray();
        $roleIds   = Role::all()->pluck('id')->toArray();

        $content = $request->all();

        $i = 0;
        foreach ($content as $key => $items) {
            if ($key != 'token') {
                foreach ($items as $item) {
                    if (in_array($item['method_id'], $methodIds)) {
                        if (in_array($item['role_id'], $roleIds)) {
                            $methodRole = new Method_role([
                                'role_id'   => $item['role_id'],
                                'method_id' => $item['method_id']
                            ]);

                            $methodRole->save();
                        } else {
                            $response = [
                                'success' => false,
                                'message' => 'Role does not exist.'
                            ];

                            return response()->json($response, 452);
                        }
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'Method does not exist.'
                        ];

                        return response()->json($response, 452);
                    }

                }
            }
        }

        $response = [
            'success' => true,
            'message' => 'New Method-Roles are created successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Get the specified Method-Role.
     *
     * @queryParam id required Method_role ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *    "id": 1,
     *    "method_id": 1,
     *    "method_name": "MethodA",
     *    "role_id": 1,
     *    "role_name": "Role1",
     *    "created_at": "2019-12-08 13:25:36",
     *    "updated_at": "2019-12-08 13:25:36"
     *   },
     *  "message": "Method_role is retrieved successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "message": "Method_role does not exist."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $method = Method::whereId($id)->first();
        if (!$method) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => "Method not found."
            ];

            return response()->json($response, 204);
        }

        $controller              = Vcontroller::whereId($method->controller_id)->first();
        $method->controller_name = $controller->name;

        $data = $method->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Method is retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Index of all Method-Roles for the specified Method ID.
     *
     * @queryParam id required Method ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *     "id": 1,
     *     "method_id": 1,
     *     "method_name": "MethodA",
     *     "role_id": 1,
     *     "role_name": "Role1",
     *     "created_at": "2019-12-08 13:25:36",
     *     "updated_at": "2019-12-08 13:25:36"
     *   },
     *   {
     *     "id": 1,
     *     "method_id": 1,
     *     "method_name": "MethodA",
     *     "role_id": 2,
     *     "role_name": "Role2",
     *     "created_at": "2019-12-08 13:25:36",
     *     "updated_at": "2019-12-08 13:25:36"
     *   }],
     *  "message": "Method-Roles are retrieved successfully."
     * }
     *
     * @response 452 {
     *    "success": false,
     *    "message": "Method does not exist."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "message": "Method-Roles does not exist."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getRoles($id)
    {
        $method = Method::whereId($id)->first();

        if (!$method) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => "Method does not exist."
            ];

            return response()->json($response, 452);
        }

        $methodRoles = Method_role::whereMethodId($id)->get();

        if ($methodRoles->count() === 0) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => "Method-Roles are absent."
            ];

            return response()->json($response, 209);
        }

        $methods = Method::all()->keyBy('id');
        $roles   = Role::all()->keyBy('id');

        $methodRoles = $this->formIndexData($methodRoles, $methods, $roles);

        $data = $methodRoles->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Method-Roles are retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Add method_name and role_name to the Method-Roles
     *
     * @param $methodRoles
     * @param $methods
     * @param $roles
     * @return
     */
    private function formIndexData($methodRoles, $methods, $roles)
    {
        foreach ($methodRoles as $methodRole) {
            $methodRole->method_name = $methods[$methodRole->method_id]->name;
            $methodRole->role_name   = $roles[$methodRole->role_id]->name;
        }

        return $methodRoles;
    }

    /**
     * Update roles for the specified Method in storage.
     *
     * @queryParam id required Method ID
     *
     * @bodyParam role_ids array required Array of role IDs with key ['ids' => [1, 3]]
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Roles of Method are updated successfully."
     * }
     *
     * @response 422 {
     *    "success": false,
     *    "message": "Method does not exist."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "message": "One of the roles does not exist."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Method",
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
        $method  = Method::whereId($id)->find(1);
        $roleIds = Role::all()->pluck('id')->toArray();

        if (!$method) {
            $response = [
                'success' => false,
                'message' => 'Method does not exist.'
            ];

            return response()->json($response, 422);
        }

        $content = $request->all();

        foreach ($content as $key => $items) {
            if ($key != 'token') {
                // check role IDs
                foreach ($items as $item) {
                    if (!in_array($item, $roleIds)) {
                        $response = [
                            'success' => false,
                            'message' => 'One of the roles does not exist.'
                        ];

                        return response()->json($response, 422);
                    }
                }
                $method->roles()->sync($items);

                $response = [
                    'success' => true,
                    'message' => 'Roles of Method are updated successfully.'
                ];

                return response()->json($response, 200);

            }
        }
    }

    /**
     * Remove the specified Method_role from storage.
     *
     * @queryParam id required Method_role ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Method_role is deleted successfully"
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Could not find Method_role."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not delete Method_role",
     *      "status_code": 500
     *    }
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $methodRole = Method_role::whereId($id)->first();

        if (!$methodRole) {
            $response = [
                'success' => false,
                'message' => 'Method-Role does not exist.'
            ];

            return response()->json($response, 422);
        }

        if ($methodRole->delete()) {
            $response = [
                'success' => true,
                'message' => 'Method-Role is deleted successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not delete Method-Role', 500);
        }
    }

    /**
     * Remove all roles of the specified Method from storage.
     *
     * @queryParam id required Method ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "All roles of the method are deleted successfully"
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "message": "Method does not exist."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not delete Method-Roles.",
     *      "status_code": 500
     *    }
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroyRoles($id)
    {
        $method = Method::whereId($id)->first();

        if (!$method) {
            $response = [
                'success' => false,
                'message' => 'Method does not exist.'
            ];

            return response()->json($response, 422);
        }

        if ($method->roles()->detach()) {
            $response = [
                'success' => true,
                'message' => 'Method-Roles are deleted successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not delete Method-Roles.', 500);
        }
    }
}
