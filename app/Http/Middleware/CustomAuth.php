<?php

namespace App\Http\Middleware;

use App\AuthToken;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomAuth implements AuthenticatesRequests
{
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $_token = str_replace("Bearer ", "", $request->headers->get('Authorization', null));

        if($_token){
            if($token = AuthToken::where(['token' => $_token])->first()){
                $this->authenticate($request, $guards);
            }
            else{
                $this->unauthenticated($request, $guards);
            }
        }

        return $next($request);

    }

    protected function authenticate($request, array $guards){

        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);

    }

    protected function unauthenticated($request, array $guards)
    {
        throw new HttpResponseException(response()->json([
           'message' => "Erreur d'autorisation"
        ])->setStatusCode(401, " Erreur d'autorisation"));
    }
}
