<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\SecurityRole;
use Exception;
use phpDocumentor\Reflection\Types\Boolean;

class JWTAuth
{   
    protected static $authenticatedUser;
    protected static $authenticated = false;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $header = $request->header('Authorization');
        
        if (!is_null($header)) {

            if ($this->CheckAuthorizationHeader($header))  return $next($request);
        }

        return response(['message'=>'not authenticated'], 401)
                    ->header('Content-Type', 'application/json');

    }

    public static function GetUser() {
        if (self::$authenticated) {

            return self::$authenticatedUser;
        }

        return null;
    }

    protected function CheckAuthorizationHeader(string $header): bool {
        $content = explode(' ', $header);
            
        if (count($content) < 2) return response(['message'=>'not authenticated'], 401)
                                        ->header('Content-Type', 'application/json');

        $type = $content[0];
        $token = $content[1];
        
        if ($type !== 'Bearer') return false;

        $key = env('AUTH_KEY');

        try {
            $payload = \Firebase\JWT\JWT::decode($token, $key, ['HS256']);

            $user = User::where([
                ['_id', '=', $payload['id']],
                ['email', '=', $payload['email']],
                ['lastName', '=', $payload['name']]
            ])->first();

            if (is_null($user)) return false;

            $role = SecurityRole::where('_id', $user->security_role_id)
                                ->first();

            if ($role->roleName !== 'admin') {

                return response(['message'=>'user not permitted'], 401)
                ->header('Content-Type', 'application/json');
            }

            self::$authenticatedUser = $user;

            return true;
        }
        catch(Exception $ex) {
            return false;
        }
    }
}
