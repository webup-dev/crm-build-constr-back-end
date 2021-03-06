<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\File;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\LeadType;
use App\Models\LsCategory;
use App\Models\Organization;
use App\Models\Requester;
use App\Models\Stage;
use App\Models\User_profile;
use App\Models\UserCustomer;
use App\Models\Workflow;
use Dingo\Api\Routing\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Controller to operate Menus
 *
 * @category Controller
 * @package  Menus
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Controller
 * @group    Menus
 */
class MenusController extends Controller
{
    use Helpers;

    /**
     * MenusController constructor.
     */
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

        $res   = [];
        $res[] = $this->_getUserProfile($collectValues);
        $res[] = $this->_getCustomers($collectValues);
        $res[] = $this->_getOrganizations($collectValues);
        $res[] = $this->_getUserCustomers($collectValues, $userProfileDepartmentId);
        $res[] = $this->_getFiles();
        $res[] = $this->_getLsCategories();
        $res[] = $this->_getLeadSources();
        $res[] = $this->_getTrades();
        $res[] = $this->_getStatuses();
        $res[] = $this->_getStages();
        $res[] = $this->_getWorkflows();
        $res[] = $this->_getRequesters();

        $response = [
            "success" => true,
            "code"    => 200,
            "message" => 'Soft-deleted retrieved successfully.',
            "data"    => $res
        ];

        return response()->json($response, 200);
    }

    /**
     * Method to get soft-deleted User Profiles
     *
     * @param array $collectValues Array of department ID
     *
     * @return array
     */
    private function _getUserProfile($collectValues)
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

    /**
     * Method to get soft-deleted User Customers
     *
     * @param array $collectValues Array of department ID
     *
     * @return array
     */
    private function _getUserCustomers($collectValues)
    {
        // 1. get all watched by current user organizations IDs ($collectValues)
        // 2. get all watched by current user customers (their IDs)
        $customerIds = Customer::withTrashed()
            ->whereIn('organization_id', $collectValues)
            ->get()
            ->pluck('id');
        // 3. get al watched by current user soft-deleted user-customers
        $userCustomersCount = UserCustomer::onlyTrashed()
            ->whereIn('customer_id', $customerIds)
            ->get()
            ->count();
        $arr = [
            "name"  => 'User-Customers',
            "url"   => 'user-customers/soft-deleted',
            "count" => $userCustomersCount
        ];

        return $arr;
    }

    /**
     * Method to get soft-deleted User Customers
     *
     * @param array $collectValues Array of department ID
     *
     * @return array
     */
    private function _getCustomers($collectValues)
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

    /**
     * Method to get soft-deleted Organizations
     *
     * @param array $collectValues Array of department ID
     *
     * @return array
     */
    private function _getOrganizations($collectValues)
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

    /**
     * Method to get soft-deleted Files
     *
     * @return array
     */
    private function _getFiles()
    {
        $filesCount = File::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Files',
            "url"   => 'files/soft-deleted',
            "count" => $filesCount
        ];
    }

    /**
     * Method to get soft-deleted Lead Source Categories
     *
     * @return array
     */
    private function _getLsCategories()
    {
        $lsCategoriesCount = LsCategory::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Lead Source Categories',
            "url"   => 'lead-source-categories/soft-deleted',
            "count" => $lsCategoriesCount
        ];
    }

    /**
     * Method to get soft-deleted Lead Sources
     *
     * @return array
     */
    private function _getLeadSources()
    {
        $leadSources = LeadSource::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Lead Sources',
            "url"   => 'lead-sources/soft-deleted',
            "count" => $leadSources
        ];
    }

    /**
     * Method to get soft-deleted Trades
     *
     * @return array
     */
    private function _getTrades()
    {
        $trades = LeadType::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Trades',
            "url"   => 'trades/soft-deleted',
            "count" => $trades
        ];
    }

    /**
     * Method to get soft-deleted Statuses
     *
     * @return array
     */
    private function _getStatuses()
    {
        $leadStatuses = LeadStatus::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Lead Statuses',
            "url"   => 'lead-statuses/soft-deleted',
            "count" => $leadStatuses
        ];
    }

    /**
     * Method to get soft-deleted Stages
     *
     * @return array
     */
    private function _getStages()
    {
        $stages = Stage::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Stages',
            "url"   => 'stages/soft-deleted',
            "count" => $stages
        ];
    }

    /**
     * Method to get soft-deleted Workflows
     *
     * @return array
     */
    private function _getWorkflows()
    {
        $workflows = Workflow::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Workflows',
            "url"   => 'workflows/soft-deleted',
            "count" => $workflows
        ];
    }

    /**
     * Method to get soft-deleted Requesters
     *
     * @return array
     */
    private function _getRequesters()
    {
        $requesters = Requester::onlyTrashed()
            ->select('id')
            ->get()
            ->count();

        return [
            "name"  => 'Requesters',
            "url"   => 'requesters/soft-deleted',
            "count" => $requesters
        ];
    }
}
