<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\User_profile;
use Dingo\Api\Routing\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Menus
 */
class MenusController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('soft_deleted_menu_organization.admin')->only(['getSoftDeleted']);
        $this->middleware('activity');
    }

    /**
     * Get data about soft-deleted items
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Soft-deleted retrieved successfully.",
     *  "data": [{
     *    "name": "User Profiles",
     *    "url": "user-profiles/soft-deleted",
     *    "count": 5
     *   },
     *   {
     *    "name": "Customers",
     *    "url": "customers/soft-deleted",
     *    "count": 2
     *   }]
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "code": 453,
     *  "message": "You do not have permission.",
     *  "data": null
     * }
     *
     * @return Response
     */
    public function getSoftDeleted()
    {
        $user                    = Auth::guard()->user();
        $userProfileDepartmentId = User_profile::with('organization')
            ->whereUserId($user->id)
            ->first()
            ->department_id;

        $organizations = Organization::withTrashed()->get()->toArray();
        $tree          = buildTree($organizations, $userProfileDepartmentId);

        $collectValues = collectValues($tree, 'id', [$userProfileDepartmentId]);
        $res           = [];
        $res[]         = $this->getUserProfile($collectValues);
        $res[]         = $this->getCustomers($collectValues);
        $res[]         = $this->getOrganizations($collectValues);

        $response = [
            "success" => true,
            "code"    => 200,
            "message" => 'Soft-deleted retrieved successfully.',
            "data"    => $res
        ];

        return response()->json($response, 200);
    }

    private function getUserProfile($collectValues)
    {
        $userProfilesCount = User_profile::onlyTrashed()
            ->whereIn('department_id', $collectValues)
            ->select('id')
            ->get()
            ->count();

        $arr = [
            "name"  => 'User Profiles',
            "url"   => 'user-profiles/soft-deleted',
            "count" => $userProfilesCount
        ];

        return $arr;
    }

    private function getCustomers($collectValues)
    {
        $customersCount = Customer::onlyTrashed()
            ->whereIn('id', $collectValues)
            ->select('id')
            ->get()
            ->count();

        $arr = [
            "name"  => 'Customers',
            "url"   => 'customers/soft-deleted',
            "count" => $customersCount
        ];

        return $arr;
    }

    private function getOrganizations($collectValues)
    {
        $organizationsCount = Organization::onlyTrashed()
            ->whereIn('id', $collectValues)
            ->select('id')
            ->get()
            ->count();

        $arr = [
            "name"  => 'Organizations',
            "url"   => 'organizations/soft-deleted',
            "count" => $organizationsCount
        ];

        return $arr;
    }
}