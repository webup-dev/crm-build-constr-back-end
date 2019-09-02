<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\Book;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @group Roles
 */
class RolesController extends Controller
{
    use Helpers;

    /**
     * Get index of roles
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "name": "Role 1",
     *    "description": "Description 1",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Role 2",
     *    "description": "Description 2",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Roles retrieved successfully"
     * }
     * @response 404 {
     *  "message": "Roles not found."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::all();
        $data  = $roles->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Roles retrieved successfully'
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created Role in storage.
     *
     * @response 200 {
     *  "success": true,
     *  "message": "New Book created successfully."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Role",
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
            'name'        => 'required|string',
            'description' => 'string',
        );

        $messages = array(
            'name.required'      => 'Please enter a name.',
            'name.string'        => 'Name must be a string.',
            'description.string' => 'Description must be a string.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }

        $role = new Role();

        $role->name        = $request->get('name');
        $role->description = $request->get('description');

        if ($role->save()) {
            $response = [
                'success' => true,
                'message' => 'New Role created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create role', 500);
        }
    }

    /**
     * Display the specified Role.
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "Role 1",
     *       "description": "Description 1",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-08 13:25:36"
     *     },
     *  "message": "Role retrieved successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "data": "Empty",
     *    "message": "Role not found."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::whereId($id)->first();
        if (!$role) {
            $response = [
                'success' => false,
                'data'    => "Empty",
                'message' => "Role not found."
            ];

            return response()->json($response, 204);
        }

        $data = $role->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Role retrieved successfully.'
        ];

        return response()->json($response, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "Role Updated",
     *       "description": "Description Updated",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36"
     *     },
     *  "message": "Role is updated successfully."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Role",
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
     * Remove the specified resource from storage.
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Role is deleted successfully"
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Could not find Role."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Role",
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
        $role = Role::whereId($id)->first();

        if (!$role) {
            $response = [
                'success' => false,
                'message' => 'Could not find Role.'
            ];

//            print_r(response()->json($response, 204));
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
