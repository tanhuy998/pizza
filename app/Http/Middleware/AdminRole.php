<?php

namespace App\Http\Middleware;

use Closure;

class AdminRole
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
        var_dump($role);
        if (!is_null($role) && $role === 'ADMIN') return $next($request);

        return response(['message' => 'not authenticated'], 401)
                ->header('Content-Type', 'application/json');
    }
}
