<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Log;
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
    public function handle($request, Closure $next)
    {
        $user_auth = $request->header('PHP_AUTH_USER');
        Log::info('OnlySuperUser::handle -> GET /api/users/iam', [$user_auth]);
        $user = User::where('email', $user_auth)->first();
        if (!$user) {
            $user = User::find($user_auth);
        }
        if (!$user) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }
        if (!$user->is_super_user) {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
