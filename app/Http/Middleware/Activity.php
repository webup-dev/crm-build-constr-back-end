<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Auth;

class Activity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle($request, Closure $next)
    {
        $user   = Auth::guard()->user();
        $uri    = $request->path();
        $method = $request->method();

        $data           = [];
        $data['uri']    = $uri;
        $data['method'] = $method;

        $data = json_encode($data);

        $activity          = new \App\Models\Activity();
        $activity->user_id = $user['id'];
        $activity->req     = $data;
        $activity->save();

        return $next($request);
    }
}
