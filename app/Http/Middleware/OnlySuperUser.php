<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Response;

class OnlySuperUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user_email = $request->header('PHP_AUTH_USER');
        $user = User::where('email', $user_email)->first();
        if (!$user->is_super_user) {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
