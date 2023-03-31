<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Models\User;

use GuzzleHttp\Client;

define('DA4FY_AUTH_VALIDATE_TOKEN_ENDPOINT', env('DA4FY_AUTH_HOST') . '/api/auth/token/');

class Authenticate
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

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
        if (!$request->header('Authorization')) {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
        $auth_array = explode(" ", $request->header('Authorization'));
        $auth_type = $auth_array[0];
        if (strtolower($auth_type) == 'bearer') {
            if (!$this->canAuthenticateWithToken($request->bearerToken(), $request)) {
                return response()->json(null, Response::HTTP_UNAUTHORIZED);
            }
        } else if (strtolower($auth_type) == 'basic') {
            $auth_decoded = explode(":", base64_decode($auth_array[1]));
            if (!$this->canAuthenticateWithCredentials($auth_decoded[0], $auth_decoded[1], $request)) {
                return response()->json(null, Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return response()->json(null, Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }

    public function canAuthenticateWithToken($token, $request) {
        $url = constant('DA4FY_AUTH_VALIDATE_TOKEN_ENDPOINT') . $token;
        try {
            $response = $this->client->request(Request::METHOD_GET, $url);
            $json_response = json_decode($response->getBody()->getContents());
            $request->headers->set('PHP_AUTH_USER', $json_response->user);
            return true;
        } catch (\Exception $e) {
            error_log($e);
            Log::error($request->method() . ' ' . $request->url(), ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function canAuthenticateWithCredentials($email, $password, $request) {
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }
        $request->attributes->add(['user_id' => $user->id]);
        return true;
    }
}
