<?php

namespace App\Http\Middleware;

use App\Models\CustomerFile;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class CustomerFiles_Author
{
    /**
     * Middleware for routes with Customers ID in URL.
     * Only author of file may go through this middleware.
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $fileId = $request->route('file_id');
        $customerFile   = CustomerFile::whereId($fileId)->first();

        if (!$customerFile) {
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

        if ($customerFile->owner_user_id === $userId) {
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
