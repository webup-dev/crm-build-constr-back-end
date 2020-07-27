<?php

namespace App\Api\V1\Controllers;

use App\Http\Requests\StoreCustomerComment;
use App\Http\Requests\UpdateCustomerComment;
use App\Models\Customer;
use App\Models\CustomerComment;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @group Customer Comments
 */
class CustomerCommentsController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('customer_comments_organization.users.customer')->only(['showAll', 'store']);
        $this->middleware('customer_comments_author')->only(['update', 'softDestroy']);
        $this->middleware('platform.admin')->only(['showAllSoftDeleted', 'restore', 'destroyPermanently']);
        $this->middleware('activity');
    }

    /**
     * Get all comments of a specific customer
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
     *  "message": "Customers are retrieved successfully.",
     *  "data": {
     *    "customer": "object",
     *    "comments": [{
     *      "id": 1,
     *      "customer_id": 1,
     *      "author_id": 16,
     *      "comment": "Comment.",
     *      "parent_id": null,
     *      "level": 1,
     *      "deleted_at": null,
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object"
     *     },
     *     {
     *      "id": 2,
     *      "customer_id": 1,
     *      "author_id": 2,
     *      "comment": "Comment.",
     *      "parent_id": 1,
     *      "level": 1,
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
     *  "message": "Permission to the department is absent."
     * }
     *
     * @param $id
     * @return Response
     */
    public function showAll($id)
    {
        $customer = Customer::whereId($id)->first();
        $comments = CustomerComment::with('user')
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
            'message' => "Customer's comments are retrieved successfully.",
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    /**
     * Get all comments of a specific customer with SoftDeleted comments
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
     *  "message": "Customers are retrieved successfully.",
     *  "data": {
     *    "customer": "object",
     *    "comments": [{
     *      "id": 1,
     *      "customer_id": 1,
     *      "author_id": 16,
     *      "comment": "Comment.",
     *      "parent_id": null,
     *      "deleted_at": "2019-06-24 07:12:03",
     *      "created_at": "2019-06-24 07:12:03",
     *      "updated_at": "2019-06-24 07:12:03",
     *      "user": "object"
     *     },
     *     {
     *      "id": 2,
     *      "customer_id": 1,
     *      "author_id": 2,
     *      "comment": "Comment.",
     *      "parent_id": 1,
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
     * @response 454 {
     *  "success": false,
     *  "message": "Permission to the department is absent."
     * }
     *
     * @param $id
     * @return Response
     */
    public function showAllSoftDeleted($id)
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
     * Create comment for a specific customer
     *
     * @bodyParam customer_id integer required Customer ID
     * @bodyParam author_id integer required Author User ID
     * @bodyParam comment text required Comment
     * @bodyParam parent_id integer Parent Comment Id
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
     *  "message": "Customer is absent.",
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
     *  "message": "Permission to the department is absent."
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
     *  "message": "Could not create Customer's comment."
     * }
     *
     * @param StoreCustomerComment $request
     * @return Response
     */
    public
    function store(StoreCustomerComment $request)
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
     * Edit data of the specified comment
     *
     * @bodyParam customer_id integer required Customer ID
     * @bodyParam author_id integer required Author User ID
     * @bodyParam comment text required Comment
     * @bodyParam parent_id integer Parent Comment Id
     *
     * @queryParam id int required Customer ID
     * @queryParam comment_id int required Comment ID
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
     *  "message": "Customer's comment is updated successfully.",
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
     *  "message": "You are not the author.",
     *  "data": null
     * }
     *
     * @param UpdateCustomerComment $request
     * @param $id
     * @param $comment_id
     * @return void
     */
    public
    function update(UpdateCustomerComment $request, $id, $comment_id)
    {
        $comment = CustomerComment::whereId($comment_id)->first();

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
     * Soft Delete customer's comment
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *     customer to his account
     *
     * @queryParam id int required Customer ID
     * @queryParam comment_id int required Comment ID
     *
     * @response 200 {
     *  "success": true,
     *  "message": "Customer's comment is soft-deleted successfully."
     * }
     *
     * @response 422 {
     *  "success": false,
     *  "code": 422,
     *  "message": "Customer is absent.",
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
     *  "message": "Permission to the department is absent."
     * }
     *
     *
     * @response 455 {
     *  "success": false,
     *  "message": "There is a child comment."
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
     * Restore customer's comment
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *     customer to his account
     *
     * @queryParam id int required Customer ID
     * @queryParam comment_id int required Comment ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's comment is restored successfully.",
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
     * Destroy customer's comment permanently
     *
     * Access:
     *   direct access:
     *     platform-admin and higher
     *   conditional access:
     *     organization-user - to customers of his organization
     *     customer to his account
     *
     * @queryParam id int required Customer ID
     * @queryParam comment_id int required Comment ID
     *
     * @response 200 {
     *  "success": true,
     *  "code": 200,
     *  "message": "Customer's comment is deleted permanently.",
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
     * @param $comment_id
     * @return void
     */
    public
    function destroyPermanently($id, $comment_id)
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
