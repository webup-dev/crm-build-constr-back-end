<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\CreateCustomerFile;
use App\Http\Requests\UpdateCustomerFile;
use App\Models\Customer;
use App\Models\CustomerFile;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * @group Customer Files
 */
class CustomerFilesController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('customer_comments_organization.users.customer')->only(['index', 'show', 'store']);
        $this->middleware('customer_files_author')->only(['update', 'softDestroy']);
        $this->middleware('platform.admin')->only(['indexSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get all files of a specific customer
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *     customer to his account
     *
     * @queryParam id int required Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer Files are retrieved successfully.",
     *  "data": {
     *    "customer": "object",
     *    "files": [{
     *      "id": 1,
     *      "customer_id": 1,
     *      "description": "Description 1",
     *      "filename": "customer-a-2019-12-01--13456579.png",
     *      "owner_user_id": 16,
     *      "deleted_at": null,
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object"
     *     },
     *     {
     *      "id": 2,
     *      "customer_id": 1,
     *      "description": "Description 2",
     *      "filename": "customer-a-2019-12-02--13456580.png",
     *      "owner_user_id": 16,
     *      "deleted_at": null,
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object"
     *     }]
     *   }
     * }
     *
     * @response 204 {
     *  "message": "No content."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect entity ID.",
     *  "data": null
     * }
     *
     * @param $id
     * @return Response
     */
    public function index($id)
    {
        $customer = Customer::whereId($id)->first();
        $files = CustomerFile::with('user')
            ->whereCustomerId($id)
            ->get();

        if ($files->count() === 0) {
            $response = [
                'success' => true,
                'code'    => 204,
                'message' => "Content is empty",
                'data'    => null
            ];

            return response()->json($response, 204);
        }

        $data = [
            'customer' => $customer->toArray(),
            'files' => $files->toArray()
        ];

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "Customer's files are retrieved successfully.",
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    /**
     * Get all files of a specific customer with SoftDeleted comments
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required Customer ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer Files with soft deleted are retrieved successfully.",
     *  "data": {
     *    "customer": "object",
     *    "files": [{
     *      "id": 1,
     *      "customer_id": 1,
     *      "description": "Description 1",
     *      "filename": "customer-a-2019-12-01--13456579.png",
     *      "owner_user_id": 16,
     *      "deleted_at": "2019-06-24 07:12:03",
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object"
     *     },
     *     {
     *      "id": 2,
     *      "customer_id": 1,
     *      "description": "Description 2",
     *      "filename": "customer-a-2019-12-02--13456580.png",
     *      "owner_user_id": 16,
     *      "deleted_at": "2019-06-24 07:12:03",
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object"
     *     }]
     *   }
     * }
     *
     * @response 204 {
     *  "message": "No content."
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect entity ID.",
     *  "data": null
     * }
     *
     * @param $id
     * @return Response
     */
    public function indexWithSoftDeleted($id)
    {
        $customer = Customer::whereId($id)->first();
        $comments = CustomerComment::onlyTrashed()
            ->whereCustomerId($id)
            ->get();

        if ($comments->count() === 0) {
            $response = [
                'success' => true,
                'code'    => 204,
                'message' => "Content is empty",
                'data'    => null
            ];

            return response()->json($response, 204);
        }

        $data = [
            'customer' => $customer->toArray(),
            'comments' => $comments->toArray()
        ];

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "Customer's soft-deleted comments are retrieved successfully.",
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    /**
     * Create file for a specific customer
     *
     * @bodyParam customer_id integer required Customer ID
     * @bodyParam description string File Description
     * @bodyParam filename string Filename
     * @bodyParam owner_user_id integer required File User-Owner ID
     *
     * @queryParam id int required Customer ID
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *     customer to his account
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Comment is created successfully.",
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
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "File extension is invalid.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect entity ID.",
     *  "data": null
     * }
     *
     * @response 500 {
     *  "message": "Could not create Customer's file."
     * }
     *
     * @param CreateCustomerFile $request
     * @return Response
     */
    public
    function store(CreateCustomerFile $request)
    {
        $data = $request->all();

        // Check is customer_id is available
        $customer = Customer::whereId($data['customer_id'])->first();

        if (!$customer) {
            $response = [
                "success" => false,
                "code"    => 456,
                "message" => "Incorrect Entity ID.",
                "data"    => null
            ];

            return response()->json($response, 456);
        }

        $user        = Auth::guard()->user();
        $userProfile = $user->user_profile;

        $organizations = Organization::all()->toArray();

        if (!isOwn($organizations, $userProfile->department_id, $customer->organization_id)) {
            $response = [
                'success' => false,
                'message' => 'Permission is absent by the role.'
            ];

            return response()->json($response, 453);
        }

        $comment = new CustomerComment([
            'customer_id' => $data['customer_id'],
            'author_id'   => $data['author_id'],
            'comment'     => $data['comment'],
            'parent_id'   => $data['parent_id'],
            'level'       => $data['level'],
        ]);

        if ($comment->save()) {
            $response = [
                'success' => true,
                'code'    => 200,
                'message' => 'Customer is created successfully.',
                'data'    => null
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error("Could not create Customer's comment", 500);
        }

    }

    /**
     * Edit data of the specified file
     *
     * @bodyParam customer_id integer required Customer ID
     * @bodyParam description string File Description
     * @bodyParam filename string Filename
     * @bodyParam owner_user_id integer required File User-Owner ID
     *
     * @queryParam id int required Customer ID
     * @queryParam file_id int required File ID
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *     customer to his account
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's file is updated successfully.",
     *  "data": null
     * }
     *
     * @response 422 {
     *  "error": {
     *    "message": "The given data was invalid.",
     *    "errors": []
     *   }
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "File extension is invalid.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 456 {
     *  "success": true,
     *  "code": 456,
     *  "message": "Incorrect Entity ID.",
     *  "data": null
     * }
     *
     * @response 457 {
     *  "success": false,
     *  "code": 457,
     *  "message": "You are not the owner.",
     *  "data": null
     * }
     *
     * @param UpdateCustomerFile $request
     * @param $customer_id
     * @param $file_id
     * @return void
     */
    public
    function update(UpdateCustomerFile $request, $customer_id, $file_id)
    {
        $comment = CustomerComment::whereId($file_id)->first();

        if (!$comment) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect Entity ID.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        $data = $request->all();

        $comment->comment = $data['comment'];

        $data = json_encode($comment);

        if ($comment->save()) {
            $response = [
                'success' => true,
                'code'    => 200,
                'data'    => $data,
                'message' => "Customer's comment is updated successfully."
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error("Could not update Customer's Comment", 500);
        }
    }

    /**
     * Soft Delete customer's file
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *     customer to his account
     *
     * @queryParam id int required Customer ID
     * @queryParam file_id int required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's files is soft-deleted successfully.",
     *  "data": null
     * }
     *
     * @response 456 {
     *  "success": true,
     *  "code": 456,
     *  "message": "Incorrect Entity ID.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to department is absent."
     * }
     *
     * @response 457 {
     *  "success": false,
     *  "code": 457,
     *  "message": "You are not the owner.",
     *  "data": null
     * }
     *
     * @param $id
     * @param $commentId
     * @return void
     * @throws \Exception
     */
    public
    function softDestroy($id, $commentId)
    {
        $comment = CustomerComment::whereId($commentId)->first();

        if (!$comment) {
            $response = [
                "success" => false,
                "code"    => 422,
                "message" => "Incorrect Entity.",
                "data"    => null
            ];

            return response()->json($response, 422);
        }

        // check is this comment the parent?
        $children = CustomerComment::whereParentId($comment->id)->first();

        if (!$children) {
            if ($comment->delete()) {
                $response = [
                    "success" => true,
                    "code"    => 200,
                    "message" => "Customer's comment is soft-deleted successfully.",
                    "data"    => null
                ];

                return response()->json($response, 200);
            } else {
                return $this->response->error("Could not delete Customer's comment.", 500);
            }
        } else {
            $response = [
                "success" => false,
                "code"    => 455,
                "message" => "There is a child comment.",
                "data"    => null
            ];

            return response()->json($response, 455);
        }
    }

    /**
     * Restore customer's file
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required Customer ID
     * @queryParam file_id int required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's file is restored successfully.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 455 {
     *  "success": false,
     *  "code": 455,
     *  "message": "There is a parent soft-deleted comment",
     *  "data": null
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @param $id
     * @param $comment_id
     * @return void
     */
    public function restore($id, $comment_id)
    {
        $customer = Customer::whereId($id)->first();
        $comment  = CustomerComment::onlyTrashed()->whereId($comment_id)->first();

        if (!$customer or !$comment) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        // Check If There is a parent soft-deleted comment
        if ($comment->parent_id) {
            $parent = CustomerComment::onlyTrashed()->whereId($comment->parent_id)->first();
            if ($parent) {
                $response = [
                    'success' => false,
                    'code'    => 455,
                    'message' => 'There is a parent soft-deleted comment',
                    'data'    => null
                ];

                return response()->json($response, 455);
            }
        }

        // Restore customer's comment
        $comment->restore();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => 'Customer Comment restored successfully.',
            'data'    => null
        ];

        return response()->json($response, 200);
    }

    /**
     * Destroy customer's file permanently
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *
     * @queryParam id int required Customer ID
     * @queryParam file_id int required File ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's file is deleted permanently.",
     *  "data": null
     * }
     *
     * @response 453 {
     *  "success": false,
     *  "message": "Permission is absent due to Role."
     * }
     *
     * @response 455 {
     *  "success": false,
     *  "code": 455,
     *  "message": "There is a child soft-deleted comment",
     *  "data": null
     * }
     *
     * @response 456 {
     *  "success": false,
     *  "code": 456,
     *  "message": "Incorrect the Entity ID in the URL.",
     *  "data": null
     * }
     *
     * @param $id
     * @param $file_id
     * @return void
     */
    public
    function destroyPermanently($id, $file_id)
    {
        $customer = Customer::whereId($id)->first();
        $comment  = CustomerComment::onlyTrashed()->whereId($file_id)->first();

        if (!$customer or !$comment) {
            $response = [
                'success' => false,
                'code'    => 456,
                'message' => 'Incorrect the Entity ID in the URL.',
                'data'    => null
            ];

            return response()->json($response, 456);
        }

        // Check If There is a child soft-deleted comment
        $child = CustomerComment::onlyTrashed()->whereParentId($comment->id)->first();
        if ($child) {
            $response = [
                'success' => false,
                'code'    => 455,
                'message' => 'There is a child soft-deleted comment',
                'data'    => null
            ];

            return response()->json($response, 455);
        }

        // Restore customer's comment
        $comment->forceDelete();

        $response = [
            'success' => true,
            'code'    => 200,
            'message' => "Customer's comment is destroyed permanently.",
            'data'    => null
        ];

        return response()->json($response, 200);
    }
}

