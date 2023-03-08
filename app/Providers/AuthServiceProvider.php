<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $auth_array = explode(" ", $request->header('Authorization'));
                $auth_decoded = explode(":", base64_decode($auth_array[1]));
                $email = $auth_decoded[0];
                $password = $auth_decoded[1];
                $user = User::where('email', $email)->first();
                if ($user && Hash::check($password, $user->password)) {
                    // POST /api/users
                    // only super users can create new users
                    if ($request->isMethod(Request::METHOD_POST) && !$user->is_super_user) {
                        return null;
                    }
                    return $user;
                }
            }
        });
    }
}
