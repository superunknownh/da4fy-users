<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

// TODO: all methods must require header "Authorization: Basic <base64Encode(user:password)>"
class UserController extends Controller
{
    public function list()
    {
        Log::info('GET /api/users', []);
        return response()->json(User::all());
    }

    public function findById($id)
    {
        Log::info("GET /api/users/$id", []);
        $user = User::find($id);
        if (!$user) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }
        return response()->json($user);
    }

    public function iam(Request $request)
    {
        $user_auth = $request->header('PHP_AUTH_USER');
        Log::info('GET /api/users/iam', [$user_auth]);
        $user = User::where('email', $user_auth)->first();
        if (!$user) {
            $user = User::find($user_auth);
        }
        if (!$user) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }
        return response()->json($user);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);
        $request->merge([
            'password' => Hash::make($request->password)
        ]);
        $user = User::create($request->all());
        Log::info('POST /api/users', [$user]);
        return response()->json($user, Response::HTTP_CREATED);
    }
}
