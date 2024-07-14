<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $slug = Str::slug($request->name);
            $check = User::where(['slug' => $slug])->count();
            $userSlug = $check > 0 ? $slug . '-' . $check : $slug;

            User::create([
                'name' => $request->name,
                'slug' => $userSlug,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['msg' => 'created'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Something went wrong! Message: ' . $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function login(LoginRequest $request)
    {
        $remember = $request->remember ? true : false;
        if (!Auth::attempt(['email' => $request->username, 'password' => $request->password], $remember)) {
            return response()->json(['error' => 'Incorrect credential'], Response::HTTP_BAD_REQUEST);
        }
        $user = Auth::user();
        $token = $user->createToken(env('APP_NAME'))->accessToken;

        return response()->json(['token' => $token, 'slug' => $user->slug], Response::HTTP_ACCEPTED);
    }

    public function me()
    {
        return UserResource::make(Auth::user());
    }

    public function getSlug()
    {
        $slug = Auth::user()->slug;

        return response()->json(['slug' => $slug], Response::HTTP_OK);
    }

    public function tokenValid()
    {
        $user = Auth::user();
        $valid = $user ? true : false;
        return response()->json([$valid], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $request->user()->token()->revoke();
        }
        return response()->json(['msg' => 'logout'], Response::HTTP_OK);
    }
}
