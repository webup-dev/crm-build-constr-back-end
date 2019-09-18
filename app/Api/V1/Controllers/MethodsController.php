<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Method;
use App\Models\Vcontroller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\Book;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @group Methods
 */
class MethodsController extends Controller
{
    use Helpers;

    /**
     * Get index of methods for specified controller
     *
     * @queryParam id required Controller ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *    "id": 1,
     *    "name": "methodA",
     *    "controller_id": 1,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "name": "methodB",
     *    "controller_id": 1,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }],
     *  "message": "Methods retrieved successfully"
     * }
     *
     * @response 204 {
     *  "success": true,
     *  "data": [],
     *  "message": "Methods are absent."
     * }
     *
     * @return Response
     */
    public function index($id)
    {
        $methods = Method::whereControllerId($id)->get();

        $data = $methods->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Methods are retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created Methods in storage for specified controller.
     *
     * @queryParam id required Controller ID
     * @bodyParam name string required Method Name
     * @bodyParam controller_id int required Controller-owner
     *
     * @response 200 {
     *  "success": true,
     *  "message": "New Method is created successfully."
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Controller does not exist"
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not create Method",
     *      "status_code": 500
     *    }
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $rules = array(
            'name'          => 'required|string',
            'controller_id' => 'required|int'
        );

        $messages = array(
            'name.required' => 'Please enter a name.',
            'name.string'   => 'Name must be a string.',
            'controller_id.required'   => 'Please select a controller.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
        }

        $method = new Method();

        $method->name = $request->get('name');
        $method->controller_id = $request->get('controller_id');

        $controllersId = Vcontroller::all()->pluck('id')->toArray();

        if (in_array($method->controller_id, $controllersId)) {
            if ($method->save()) {
                $response = [
                    'success' => true,
                    'message' => 'New Method is created successfully.'
                ];
                return response()->json($response, 200);
            } else {
                return $this->response->error('Could not create method', 500);
            }
        }

        // @todo the body is not included
        $response = [
            'success' => false,
            'message' => 'Controller does not exist.'
        ];
        return response()->json($response, 204);
//        return $this->response->error('Controller does not exist.', 204);

    }

    /**
     * Display the specified Method.
     *
     * @queryParam id required Method ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *     "id": 1,
     *     "name": "MethodA",
     *     "controller_id": 1,
     *     "created_at": "2019-12-08 13:25:36",
     *     "updated_at": "2019-12-08 13:25:36"
     *  },
     *  "message": "Method is retrieved successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "message": "Method does not exist."
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

        $data = $method->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Method is retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Update the specified Method in storage.
     *
     * @queryParam id required Method ID
     *
     * @bodyParam name string required Method Name
     * @bodyParam controller_id int required Controller ID
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "MethodUpdated",
     *       "controller_id": 1,
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-09 13:25:36"
     *     },
     *  "message": "Method is updated successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "message": "Method does not exist."
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
        $method = Method::whereId($id)->first();

        if (!$method) {
            $response = [
                'success' => false,
                'message' => 'Method does not exist.'
            ];

            return response()->json($response, 422);
        }

        $method->fill($request->all());

        $data = $method;

        if ($method->save()) {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Method is updated successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not update Method', 500);
        }
    }

    /**
     * Remove the specified Method from storage.
     *
     * @queryParam id required Method ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Method is deleted successfully"
     * }
     *
     * @response 204 {
     *  "success": false,
     *  "message": "Could not find Method."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not delete Method",
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
        $method = Method::whereId($id)->first();

        if (!$method) {
            $response = [
                'success' => false,
                'message' => 'Method does not exist.'
            ];

            return response()->json($response, 422);
        }

        if ($method->delete()) {
            $response = [
                'success' => true,
                'message' => 'Method is deleted successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not delete Method', 500);
        }
    }
}
