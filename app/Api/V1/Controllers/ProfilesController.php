<?php

namespace App\Api\V1\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
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
     * Get the specified User.
     *
     * @response 200 {
     *  "success": true,
     *  "data": {
     *       "id": 1,
     *       "name": "Jon Daw",
     *       "created_at": "2019-12-08 13:25:36",
     *       "updated_at": "2019-12-08 13:25:36"
     *     },
     *  "message": "User retrieved successfully."
     * }
     *
     * @response 204 {
     *    "success": false,
     *    "data": "Empty",
     *    "message": "User not found."
     * }
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getUser($id)
    {
        $user = User::whereId($id)->first();
        if (!$user) {
            $response = [
                'success' => false,
                'data'    => "Empty",
                'message' => "User not found."
            ];

            return response()->json($response, 204);
        }

        $data = $user->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'User retrieved successfully.'
        ];

        return response()->json($response, 200);
    }
}
