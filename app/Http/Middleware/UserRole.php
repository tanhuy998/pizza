<?php

namespace App\Http\Middleware;

use Closure;
use App\http\Middleware\JWTAuth;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $user = JWTAuth::GetUser();
        $role = JWTAuth::Role();

        if (!is_null($role)) return $next($request);

        return response(['message' => 'not authenticated'], 401)
                ->header('Content-Type', 'application/json');
    }
}
