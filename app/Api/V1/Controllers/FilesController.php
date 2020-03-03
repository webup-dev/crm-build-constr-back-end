<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\StoreFile;
use App\Api\V1\Requests\UpdateFile;
use App\Http\Requests\StoreUserDetails;
use App\Http\Requests\UpdateUserDetails;
use App\Http\Requests\UpdateUserProfile;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\File;
use App\Models\Organization;
use App\Models\UserCustomer;
use App\Models\UserDetail;
use Config;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User_profile;
use Auth;
use Tymon\JWTAuth\JWTAuth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\Responses;
use Illuminate\Support\Facades\Storage;

/**
 * @group Files
 */
class FilesController extends Controller
{
    use Helpers;
    use Responses;

    public function __construct()
    {
        /**
         * Permissions:
         * show, edit: show_edit_file
         * create: create_file
         * softDelete: file_owner
         * indexSoftDeleted, restore, deletePermanently: platform level
         */
        $this->middleware('index_file')->only(['index']);
//        $this->middleware('show_edit_file')->only(['show', 'update']);
        $this->middleware('show_edit_file')->only(['show', 'update', 'getFile']);
        $this->middleware('create_file')->only(['create']);
        $this->middleware('file_owner')->only(['softDestroy']);
        $this->middleware('platform.admin')->only(['indexSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get index of files
     *
     * Access:
     *   all authenticated, but:
     *   platform level users: to all files
     *   organizational users: to own customers files only
     *   users-customers: to own files only
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "owner_object_type": "customer",
     *    "owner_object_id": 1,
     *    "description": "Description text",
     *    "filename": "customer_1_test-file-1368415461.jpg",
     *    "owner_user_id": "16",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "owner_object_type": "user",
     *    "owner_object_id": 16,
     *    "description": "Description text",
     *    "filename": "user_16_test-file-2368415461.jpg",
     *    "owner_user_id": "16",
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }]
     * }
     *
     * @response 204 {
     *  "success": true,
     *  "code": 204,
     *  "message": "Empty content.",
     *  "data": null
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 454 {
     *   "success": false,
     *   "code": 454,
     *   "message":  "Middleware.Files. Permission to department is absent.",
     *   "data": null
     * }
     *
     * @response 456 {
     *   "success": false,
     *   "code": 456,
     *   "message":  "Middleware.Files. Incorrect ID in URL.",
     *   "data": null
     * }
     *
     * @response 458 {
     *   "success": false,
     *   "code": 458,
     *   "message":  "Middleware.Files. Private information.",
     *   "data": null
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($customerId)
    {
        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        $roleLevel = $this->checkRoleLevel($roleNamesArr);

        switch ($roleLevel) {
            case "platform":
                $resp = $this->indexPlatform($customerId);
                return response()->json($resp, $resp['code']);
                break;
            case "organization":
                $resp = $this->indexOrganization($customerId);
                return response()->json($resp, $resp['code']);
                break;
            case "customer":
                $resp = $this->indexCustomer($customerId);
                return response()->json($resp, $resp['code']);
                break;
        }
    }

    private function indexPlatform($customerId)
    {
        $files = File::with('user')
            ->whereOwnerObjectType('customer')
            ->whereOwnerObjectId($customerId)
            ->get();

        if ($files->count() === 0) {
            return $this->resp(209, 'Files.index');
        }

        return $this->resp(200, 'Files.index', $files);
    }

    private function indexOrganization($customerId)
    {
        $files = File::whereOwnerObjectType('customer')
            ->whereOwnerObjectId($customerId)
            ->get();

        if ($files->count() === 0) {
            return $this->resp(209, 'Files.index');
        }

        return $this->resp(200, 'Files.index', $files);
    }

    private function indexCustomer($customerId)
    {
        $files = File::whereOwnerObjectType('customer')
            ->whereOwnerObjectId($customerId)
            ->get();

        if ($files->count() === 0) {
            return $this->resp(209, 'Files.index');
        }

        return $this->resp(200, 'Files.index', $files);
    }

    private function checkRoleLevel($roleNamesArr)
    {
        if (one_from_arr_in_other_arr([
            'developer',
            'platform-superadmin',
            'platform-admin'
        ], $roleNamesArr)) {
            return 'platform';
        }

        if (one_from_arr_in_other_arr([
            'organization-superadmin',
            'organization-admin',
            'organization-general-manager',
            'organization-sales-manager',
            'organization-production-manager',
            'organization-administrative-leader',
            'organization-estimator',
            'organization-project-manager',
            'organization-administrative-assistant'
        ], $roleNamesArr)) {
            return 'organization';
        }

        if (one_from_arr_in_other_arr([
            'customer-individual',
            'customer-organization'
        ], $roleNamesArr)) {
            return 'customer';
        }

        return 'guest';
    }

    /**
     * Get index of soft-deleted files
     *
     * Access:
     *   direct access:
     *     superadmin
     *     platform-superadmin
     *     developer
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Result is successful.",
     *  "data": [{
     *    "id": 1,
     *    "owner_object_type": "customer",
     *    "owner_object_id": 1,
     *    "description": "Description text",
     *    "filename": "customer_1_test-file-1368415461.jpg",
     *    "owner_user_id": "16",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   },
     *   {
     *    "id": 2,
     *    "owner_object_type": "user",
     *    "owner_object_id": 16,
     *    "description": "Description text",
     *    "filename": "user_16_test-file-2368415461.jpg",
     *    "owner_user_id": "16",
     *    "deleted_at": "2019-06-24 07:12:03",
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *   }]
     * }
     *
     * @response 204 {
     *  "success": true,
     *  "code": 204,
     *  "message": "Empty content.",
     *  "data": null
     * }
     *
     * @response 453 {
     * "success": false,
     * "code": 453,
     * "message":  "Permission is absent by the role.",
     * "data": null
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexSoftDeleted()
    {
        $files = File::onlyTrashed()
            ->select('id', 'owner_object_type', 'owner_object_id', 'description', 'filename', 'owner_user_id', 'deleted_at', 'created_at', 'updated_at')
            ->get();

        if (!$files->count()) {
            return response()->json($this->resp(204, 'Files.indexSoftDeleted'), 204);
        }

        $files = $this->addObjectsToIndexSoftDeleted($files);

        return response()->json($this->resp(200, 'Files.indexSoftDeleted', $files), 200);
    }

    private function addObjectsToIndexSoftDeleted($files)
    {
        foreach ($files as $file) {
            $file->author = User::whereId($file->owner_user_id)->first();

            if ($file->owner_object_type === 'customer') {
                $file->owner_object = Customer::whereId($file->owner_object_id)->first();
            }

            if ($file->owner_object_type === 'user') {
                $file->owner_object = $file->author;
            }
        }

        return $files;
    }

    /**
     * Get data of the specified file
     *
     * Access:
     *
     *   direct access: platform level
     *
     *   conditional access: organizational users - to files of their organization, user - to own customer profile
     *
     * @queryParam id required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Result is successful.",
     *  "data": {
     *    "id": 1,
     *    "owner_object_type": "customer",
     *    "owner_object_id": 1,
     *    "description": "Description text",
     *    "filename": "customer_1_test-file-1368415461.jpg",
     *    "owner_user_id": 16,
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *  }
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 454 {
     *   "success": false,
     *   "code": 454,
     *   "message":  "Middleware.Files. Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 456 {
     *   "success": false,
     *   "code": 456,
     *   "message":  "Middleware.Files. Incorrect ID in URL.",
     *   "data": null
     * }
     *
     * @param $id
     * @return void
     */
    public
    function show($id)
    {
        $file = File::whereId($id)
            ->first();

        if (!$file) {

            return response()->json($this->resp(456, 'Files.show'), 456);
        }

        return response()->json($this->resp(200, 'Files.show', $file), 200);
    }

    /**
     * Get specified file
     * Access:
     *   direct access: platform level
     *   conditional access: organizational users - to files of their organization,
     *   user - to own customer files
     *
     * @param $id File ID
     *
     * @queryParam id required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Result is successful.",
     *  "data": {
     *    "id": 1,
     *    "owner_object_type": "customer",
     *    "owner_object_id": 1,
     *    "description": "Description text",
     *    "filename": "customer_1_test-file-1368415461.jpg",
     *    "owner_user_id": 16,
     *    "deleted_at": null,
     *    "created_at": "2019-06-24 07:12:03",
     *    "updated_at": "2019-06-24 07:12:03"
     *  }
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 454 {
     *   "success": false,
     *   "code": 454,
     *   "message":  "Middleware.Files. Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 456 {
     *   "success": false,
     *   "code": 456,
     *   "message":  "Middleware.Files. Incorrect ID in URL.",
     *   "data": null
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFile($id)
    {
        $file = File::whereId($id)
            ->first();

        if (!$file) {
            return response()->json($this->resp(456, 'Files.getFile'), 456);
        }

        $exist = Storage::disk('public')->exists($file->filename);
        if (!$exist) {
            return response()->json($this->resp(456, 'Files.getFile'), 456);
        }

        $size = Storage::disk('public')->size($file->filename);
        $headers = [
            header('Content-Description: File Transfer'),
            header('Content-Type: application/octet-stream'),
            header('Content-Disposition: attachment; filename=' . $file->filename),
            header('Expires: 0'),
            header('Cache-Control: must-revalidate'),
            header('Pragma: public'),
            header('Content-Length: ' . $size)
        ];

        return Storage::disk('public')->download($file->filename, $file->filename, $headers);
    }

    /**
     * Create file
     *
     * @bodyParam owner_object_type str required Owner Object Type
     * @bodyParam owner_object_id int required Owner Object ID
     * @bodyParam description string Description
     * @bodyParam filename string required Filename
     * @bodyParam owner_user_id int required Author ID
     * @bodyParam deleted_at string Date of Soft-deleting or Null
     *
     * Access:
     *   direct access: platform level
     *   conditional access: users with organizational level to own customers
     *                       users with customer level to qwn files
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Result is successful.",
     *  "data": null
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "The given data was invalid.",
     *  "data": null
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Middleware.Files. Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 454 {
     *   "success": false,
     *   "code": 454,
     *   "message":  "Middleware.Files. Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 456 {
     *   "success": false,
     *   "code": 456,
     *   "message":  "Middleware.Files. Incorrect ID in URL.",
     *   "data": null
     * }
     *
     * @response 459 {
     *   "success": false,
     *   "code": 459,
     *   "message":  "Middleware.Files. File extension is invalid.",
     *   "data": null
     * }
     *
     *
     * @param StoreFile $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function store(StoreFile $request)
    {
        $user             = Auth::guard()->user();
        $roles            = $user->roles;
        $roleNamesArr     = $roles->pluck('name')->all();
        $isCustomer       = isCustomer($roleNamesArr);
        $isOrganizational = isOrganizational($roleNamesArr);

        $data = $request->all();

        $file = new File();
        $file->fill($data);
        $file->owner_user_id = $user->id;

        // check entity id
        if (!$this->checkEntityId($file)) {
            return response()->json($this->resp(422, 'Files.store'), 422);
        }

        switch ($this->checkCanUserOperateWithFile($file, $isCustomer, $user)) {
            case 453:
                return response()->json($this->resp(453, 'Files.store'), 453);
                break;

            case 454:
                return response()->json($this->resp(454, 'Files.store'), 454);
                break;

            case 'Ok':
                break;
        }

        if ($isOrganizational) {
            switch ($this->canOrgUserOperateWithCurrentCustomer($file, $user)) {
                case 454:
                    return response()->json($this->resp(454, 'Files.store'), 454);

                case 'Ok':
                    break;
            }
        }

        if ($request->hasFile('photo')) {
            // first save file on the disk
            $fileName = $file->owner_object_type . '-' . $file->owner_object_id . '-' . date('Y-m-d_h-i-s') . ('-') . rand(0, 999) . '.' . $request->photo->extension();
            $request->photo->storeAs('public', $fileName);

            $file->filename = $fileName;
        } else {
            return response()->json($this->resp(422, 'Files.store. File is absent.'), 422);
        }

        if ($file->save()) {
            return response()->json($this->resp(200, 'Files.store'), 200);
        } else {
            return response()->json($this->resp(500, 'Files.store'), 500);
        }
    }

    private function checkEntityId($file)
    {
        // check entity id
        if ($file->owner_object_type === 'user') {
            $userTest = User::whereId($file->owner_object_id)->first();

            if (!$userTest) {
                return false;
            }
        }

        if ($file->owner_object_type === 'customer') {
            $customer = Customer::whereId($file->owner_object_id)->first();
            $userTest = User::whereId($file->owner_user_id)->first();

            if (!$customer || !$userTest) {
                return false;
            }
        }

        return true;
    }

    private function checkCanUserOperateWithFile($file, $isCustomer, $user)
    {
        // type = 'user'
        // if type is user check is author a given customer
        if ($file->owner_object_type === 'user' and !$isCustomer) {
            return 453;
        }

        // type = 'customer'
        // check can the user to operate with files for the given customer
        if ($isCustomer) {
            // user's customer id must be equal owner_object_id
            // get all user's customer ids
            $customerIds = UserCustomer::whereUserId($user->id)
                ->get()
                ->pluck('customer_id')
                ->all();
            if (!in_array($file->owner_object_id, $customerIds)) {
                return 454;
            }
        }

        return 'Ok';
    }

    private function canOrgUserOperateWithCurrentCustomer($file, $user)
    {
        // user's Organization ID must be equal to customer's organization ID
        // user's Organization ID
        $userOrganizationId = User_profile::whereUserId($user->id)
            ->first()
            ->department_id;

        // customer's organization ID
        $customerOrganizationId = Customer::whereId($file->owner_object_id)
            ->first()
            ->organization_id;

        if ($userOrganizationId !== $customerOrganizationId) {
            return 454;
        }

        return 'Ok';
    }

    /**
     * Edit data of the specified file
     *
     * Access:
     *   direct access: platform level
     *   conditional access: users with organizational level to own customers
     *                       users with customer level to own file
     *
     * @bodyParam owner_object_type str required Owner Object Type
     * @bodyParam owner_object_id int required Owner Object ID
     * @bodyParam description string Description
     * @bodyParam filename string required Filename
     * @bodyParam owner_user_id int required Author ID
     * @bodyParam deleted_at string Date of Soft-deleting or Null
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Result is successful.",
     *  "data": null
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "The given data was invalid.",
     *  "data": null
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 454 {
     *   "success": false,
     *   "code": 454,
     *   "message":  "Middleware.Files. Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 456 {
     *   "success": false,
     *   "code": 456,
     *   "message":  "Middleware.Files. Incorrect ID in URL.",
     *   "data": null
     * }
     *
     * @response 457 {
     *   "success": false,
     *   "code": 457,
     *   "message":  "Middleware.Files. You are not the author.",
     *   "data": null
     * }
     *
     * @response 459 {
     *   "success": false,
     *   "code": 459,
     *   "message":  "Middleware.Files. File extension is invalid.",
     *   "data": null
     * }
     *
     * @param UpdateFile $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function update(UpdateFile $request, $id)
    {
        /**
         * Platform level may edit
         * Organizational level may edit files of own customers
         * Customer level may edit own files
         *
         * Method may change file description only.
         */
        $file = File::whereId($id)->first();

        $user         = Auth::guard()->user();
        $roles        = $user->roles;
        $roleNamesArr = $roles->pluck('name')->all();

        $file->fill($request->all());

        if ($file->save()) {
            return response()->json($this->resp(200, 'Files.update', $file), 200);
        } else {
            return response()->json($this->resp(500, 'Files.update'), 500);
        }
    }

    /**
     * Soft Delete file
     *
     * Access:
     *   access: own file
     *
     * @queryParam id int required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "User Details are soft-deleted successfully.",
     *  "data": null
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect entity ID.",
     *  "data": null
     * }
     *
     * @response 457 {
     *  "success": false,
     *  "code": 457,
     *  "message": "You are not the author.",
     *  "data": null
     * }
     *
     * @param $id
     * @return void
     * @throws \Exception
     */
    public
    function softDestroy($id)
    {
        $file = File::whereId($id)->first();
        if (!$file) {
            return response()->json($this->resp(456, 'Files.softDestroy'), 456);
        }

        $file->delete();

        return response()->json($this->resp(200, 'Files.softDestroy'), 200);
    }

    /**
     * Restore file
     *
     * Access:
     *   direct access: platform level
     *
     * @queryParam id int required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "File is restored successfully.",
     *  "data": null
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Middleware.Files. Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 456 {
     *   "success": false,
     *   "code": 456,
     *   "message":  "Middleware.Files. Incorrect ID in URL.",
     *   "data": null
     * }
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $file = File::onlyTrashed()->whereId($id)->first();

        if (!$file) {
            return response()->json($this->resp(456, 'Files.restore'), 456);
        }

        // Restore file
        $file->restore();

        return response()->json($this->resp(200, 'Files.restore'), 200);
    }

    /**
     * Destroy file permanently
     *
     * Access: platform level
     *
     * @queryParam id int required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "File is restored successfully.",
     *  "data": null
     * }
     *
     * @response 453 {
     *   "success": false,
     *   "code": 453,
     *   "message":  "Middleware.Files. Permission is absent by the role.",
     *   "data": null
     * }
     *
     * @response 456 {
     *   "success": false,
     *   "code": 456,
     *   "message":  "Middleware.Files. Incorrect ID in URL.",
     *   "data": null
     * }
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function destroyPermanently($id)
    {
        $file = File::withTrashed()->whereId($id)->first();
        if (!$file) {
            return response()->json($this->resp(456, 'Files.destroyPermanently'), 456);
        }

        $file->forceDelete();

        // second delete from disk
        $this->_remove($file);

        return response()->json($this->resp(200, 'Files.destroyPermanently'), 200);
    }

    /**
     * Remove file
     *
     * @param $file File identity from DB
     *
     * @return bool
     */
    private function _remove($file)
    {
        // second delete from disk
        Storage::delete('public/' . $file->filename);

        return true;
    }
}
