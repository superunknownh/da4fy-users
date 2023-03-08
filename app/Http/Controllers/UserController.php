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
        return response()->json(User::find($id));
    }

    public function iam(Request $request)
    {
        $user_email = $request->header('PHP_AUTH_USER');
        Log::info('GET /api/users/iam', [$user_email]);
        return response()->json(User::where('email', $user_email)->first());
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
