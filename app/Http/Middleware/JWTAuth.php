<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\SecurityRole;
use Exception;
use phpDocumentor\Reflection\Types\Boolean;
use Tymon\JWTAuth\Payload;

class JWTAuth
{   
    protected static $authenticatedUser;
    protected static $authenticated = false;
    protected static $highestrole;
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

    public static function Role() {
        
        return self::$highestrole;
    }

    protected function CheckAuthorizationHeader(string $header): bool {
        $content = explode(' ', $header);
        //var_dump($content);
        if (count($content) < 2) {
            // return response(['message'=>'not authenticated'], 401)
            //         ->header('Content-Type', 'application/json');
            return false;
        }
        
        $type = $content[0];
        $token = $content[1];
        
        if ($type !== 'Bearer') return false;

        $key = env('AUTH_KEY');
        
        try {
            $payload = \Firebase\JWT\JWT::decode($token, $key, ['HS256']);
            //echo $payload->id;
            $user = User::where([
                ['_id', '=', $payload->id],
                ['email', '=', $payload->email],
                ['name', '=', $payload->name]
            ])->first();
            
            //$user = SecurityRole::all();
        
            if (is_null($user)) return false;
            
            //$role = SecurityRole::where('_id', $user->security_role_id)
                                //->first();
            
            $roles = $user->roles;
            $role = null;

            foreach ($roles as $current) {
                
                $role = $current;
                if ($current['roleName'] === 'ADMIN') {

                    break;
                }
            }

            //if ($role->roleName !== 'ADMIN') {
            if (is_null($role)) {
                // return response(['message'=>'user not permitted'], 401)
                // ->header('Content-Type', 'application/json');
                return false;
            }

            self::$authenticatedUser = $user;
            self::$highestrole = $role['roleName'];
            return true;
        }
        catch(Exception $ex) {
            
            //echo $ex->getMessage();
            return false;
        }
    }
}
