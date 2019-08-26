<?php

namespace App\Api\V1\Controllers;

use App\Models\User;
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
//        $data = response()->json(Auth::guard()->user());
//
//        if (is_null($data)) {
//            $response = [
//                'success' => false,
//                'data'    => 'Empty',
//                'message' => 'User not found.'
//            ];
//            return response()->json($response, 404);
//        }
//        $response = [
//            'success' => true,
//            'data'    => $data,
//            'message' => 'User retrieved successfully.'
//        ];

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
}
