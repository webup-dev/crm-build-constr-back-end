<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vcontroller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\Book;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @group Controllers
 */
class VcontrollersController extends Controller
{
    use Helpers;

    /**
     * Get index of controllers
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "name": "Controller1",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "Controller2",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Controllers retrieved successfully"
     * }
     * @response 404 {
     *  "message": "Controllers not found."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $controllers = Vcontroller::all();
        $data        = $controllers->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Vcontrollers retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created Controller in storage.
     *
     * @bodyParam name string required Controller Name
     *
     * @response 200 {
     *  "success": true,
     *  "message": "New Controller created successfully."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Controller",
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
            'name' => 'required|string'
        );

        $messages = array(
            'name.required' => 'Please enter a name.',
            'name.string'   => 'Name must be a string.'
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }

        $controller = new Vcontroller();

        $controller->name = $request->get('name');

        if ($controller->save()) {
            $response = [
                'success' => true,
                'message' => 'New Controller created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create controller', 500);
        }
    }

    /**
     * Display the specified Controller.
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "ControllerA",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-08 13:25:36"
     *     },
     *  "message": "Controller retrieved successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "data": "Empty",
     *    "message": "Controller not found."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $controller = Vcontroller::whereId($id)->first();
        if (!$controller) {
            $response = [
                'success' => false,
                'data'    => "Empty",
                'message' => "Controller not found."
            ];

            return response()->json($response, 422);
        }

        $data = $controller->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Controller retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Update the specified Controller in storage.
     *
     * @queryParam id required Controller ID
     *
     * @bodyParam id int required Controller ID
     * @bodyParam name string required Controller Name
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "ControllerUpdated",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36"
     *     },
     *  "message": "Controller is updated successfully."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Controller",
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
        $controller = Vcontroller::whereId($id)->first();

        if (!$controller) {
            $response = [
                'success' => false,
                'message' => 'Controller does not exist.'
            ];

            return response()->json($response, 422);
        }

        $controller->fill($request->all());

        $data = $controller;

        if ($controller->save()) {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Controller is updated successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update Controller', 500);
        }
    }

    /**
     * Remove the specified Controller from storage.
     *
     * @queryParam id required Controller ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Controller is deleted successfully"
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Could not find Controller."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not update Controller",
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
        $controller = Vcontroller::whereId($id)->first();

        if (!$controller) {
            $response = [
                'success' => false,
                'message' => 'Controller does not exist.'
            ];

            return response()->json($response, 422);
        }

        if ($controller->delete()) {
            $response = [
                'success' => true,
                'message' => 'Controller is deleted successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not delete Controller', 500);
        }
    }
}
