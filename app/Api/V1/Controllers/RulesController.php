<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Method;
use App\Models\Method_role;
use App\Models\User;
use App\Models\User_role;
use App\Models\Vcontroller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Rules
 */
class RulesController extends Controller
{
    use Helpers;

    /**
     * Get rules for requested user
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *    "permissions": {},
     *    "restrictions": {},
     *    "roles": [{
     *      "1": {
     *        "0": "1",
     *        "1": "2"
     *      }},
     *      {
     *        "2": {
     *        "0": "3"
     *      }}],
     *    "names": [{
     *      "1": {
     *        "controller": "Controller1",
     *        "methods": {
     *          "1": "MethodA",
     *          "2": "MethodB"
     *     }}},
     *     {
     *      "2": {
     *        "controller": "Controller2",
     *        "methods": {
     *          "3": "MethodC"
     *     }}}]
     *  },
     *  "message": "Rules are retrieved successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "message": "Roles does not exist."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not get Rules",
     *      "status_code": 500
     *    }
     * }
     */
    public function getRules()
    {
        /**
         * Get user (from Auth)
         * Get user's roles (from DB table user_roles)
         * Get all methods that concerns to roles
         * Get all controllers that concerns to our methods
         * Format Data
         *
         * //     *  "data": {
         * //     *    "permissions": {}
         * //     *    "restrictions": {}
         * //     *    "roles": [{
         * //     *      1: {
         * //     *        0: 1,
         * //     *        1: 2
         * //     *      },
         * //     *      2: {
         * //     *        0: 3
         * //     *    }],
         * //     *    "names": [{
         * //     *      1: {
         * //     *      "controller": "Controller1",
         * //     *      "methods": {
         * //     *        1: "MethodA"
         * //     *        2: "MethodB"
         * //     *      }}},
         * //     *      {
         * //     *      2: {
         * //     *      "controller": "Controller2",
         * //     *      "methods": {
         * //     *        3: "MethodC"
         * //     *      }}}]
         * //     *  },
         */
        // Get user (from Auth)
        // object Models/User
        $user = Auth::guard()->user();

        // Get user's roles
        // Collection of objects Models/Role
        $roles = $user->roles;

        // Get all methods that concerns to roles
        $roleIdsArr       = $roles->pluck('id')->toArray();
        $methodIdsArr     = Method_role::whereIn('role_id', $roleIdsArr)
            ->get()
            ->pluck('method_id')
            ->toArray();
        $methodIdsArr     = array_unique($methodIdsArr);
        $controllerIdsArr = Method::whereIn('id', $methodIdsArr)
            ->get()
            ->pluck('controller_id')
            ->toArray();
        $controllerIdsArr = array_unique($controllerIdsArr);

        // Get all controllers that concerns to our methods
        $controllers = Vcontroller::whereIn('id', $controllerIdsArr)->get();

        // Format Data
        $data                 = [];
        $data['permissions']  = [];
        $data['restrictions'] = [];
        $data['roles']        = [];
        $data['names']        = [];

        foreach ($controllers as $controller) {
            $controllerId       = $controller->id;
            $currMethods        = $controller->methods;
            $currMethodIdsArr   = $currMethods->pluck('id')->toArray();
            $currMethodsWithKey = $currMethods->keyBy('id');
            foreach ($currMethodIdsArr as $currMethodId) {
                if (in_array($currMethodId, $methodIdsArr)) {
                    $data['roles'][$controllerId][]                         = $currMethodId;
                    $data['names'][$controllerId]['controller']             = $controller->name;
                    $data['names'][$controllerId]['methods'][$currMethodId] = $currMethodsWithKey[$currMethodId]->name;
                }
            }
        }

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Rules are retrieved successfully.'
        ];

        return response()->json($response, 200);

    }

    /**
     * Get main role for requested user
     * The main role must be first in the DB table user-roles
     *
     * @response 200 {
     *  "success": true,
     *  "data": "superadmin",
     *  "message": "The main role is retrieved successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "data": [],
     *    "message": "You do not have any Roles."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not get main Role",
     *      "status_code": 500
     *    }
     * }
     */
    public function getMainRole()
    {
        /**
         * Get user
         * Get first role
         * Return [id=>role_id, name=>role_name]
         */

        // Get user (from Auth)
        // object Models/User
        $user = Auth::guard()->user();

        // Get user's roles
        // Collection of objects Models/Role
        $roles = $user->roles;

        // Format data
        $array = [];

        if ($roles->count() === 0) {
            $response = [
                'success' => false,
                'data'    => [],
                'message' => 'Roles are absent.'
            ];

            return response()->json($response, 200);
        }

        $mainRole      = $roles[0];
        $array['id']   = $mainRole->id;
        $array['name'] = $mainRole->name;

        $response = [
            'success' => true,
            'data'    => $array,
            'message' => 'The main role is retrieved successfully.'
        ];

        return response()->json($response, 200);
    }
}
