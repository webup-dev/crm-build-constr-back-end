<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Activites
 */
class ActivitiesController extends Controller
{
    use Helpers;

    /**
     * Get all activities from DB (+ all users)
     *
     * @response 200 {
     *  "success": true,
     *  "data": [{
     *   "id": 1,
     *   "user_id": 1,
     *   "user_name": "Paul Jones",
     *   "req": {
     *     "uri": "api/book",
     *     "method": "GET"
     *   },
     *   "created_at": "2019-06-24 07:12:03",
     *   "updated_at": "2019-06-24 07:12:03"
     *  },
     *  {
     *   "id": 2,
     *   "user_id": 1,
     *   "user_name": "Paul Jones",
     *   "req": {
     *     "uri": "api/book",
     *     "method": "POST"
     *   },
     *   "created_at": "2019-06-24 07:12:03",
     *   "updated_at": "2019-06-24 07:12:03"
     *  }],
     *  "message": "Activities are retrieved successfully"
     * }
     *
     * @response 401 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $activities = Activity::all();

        foreach ($activities as $activity) {
            $user                = $activity->user;
            $activity->user_name = $user->name;
        }
        $data = $activities->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Activities are retrieved successfully.'
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove all activities from DB.
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Activities are deleted successfully."
     * }
     *
     * @response 401 {
     *  "success": false,
     *  "message": "Permission is absent by the role."
     * }
     *
     * @response 500 {
     *  "error": {
     *      "message": "Could not delete activities",
     *      "status_code": 500
     *    }
     * }
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy()
    {
        Activity::truncate();

        $response = [
            'success' => true,
            'message' => 'Activities are deleted successfully.'
        ];

        return response()->json($response, 200);
    }
}
