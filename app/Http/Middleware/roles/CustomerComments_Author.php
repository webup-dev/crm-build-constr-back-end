<?php

namespace App\Http\Middleware;

use App\Models\CustomerComment;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class CustomerComments_Author
{
    /**
     * Middleware for routes with Customers ID in URL.
     * Only author of comments may go through this middleware.
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $customerId = $request->route('id');
        $commentId = $request->route('comment_id');
        $customerComment   = CustomerComment::whereId($commentId)->first();

        if (!$customerComment) {
            $response = [
                'success' => false,
                'code'    => 456,
                "message" => "Incorrect entity ID.",
                "data"    => null
            ];

            return response()->json($response, 456);
        }

        $user   = Auth::guard()->user();
        $userId = $user->id;

        if ($customerComment->author_id === $userId) {
            return $next($request);
        } else {
            $response = [
                'success' => false,
                'code'    => 457,
                "message" => "You are not the author.",
                "data"    => null
            ];

            return response()->json($response, 457);
        }
    }
}
