<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Auth;
use App\Traits\Responses;

class File_owner
{
    use Responses;

    /**
     * Middleware for methods SoftDelete of FilesController
     * Handle an incoming request.
     *
     * Permission to own file only
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $currentUser = Auth::guard()->user();
        $idFromUrl   = $request->route('id');
        $file        = File::whereId($idFromUrl)->first();

        if (!$file) {
            return response()->json($this->resp(456, 'middleware.Files'), 456);
        }

        if ($currentUser->id === $file->owner_user_id) {
            return $next($request);
        }

        return response()->json($this->resp(457, 'middleware.Files'), 457);
    }
}
