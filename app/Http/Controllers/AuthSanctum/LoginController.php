<?php

namespace App\Http\Controllers\AuthSanctum;

use App\Http\Controllers\UserInfoController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//use Laravel\Sanctum\Http\Controllers\SanctumController;

class LoginController
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Attempt login using Laravel authentication
//        dd(config('auth.guards'));
        foreach (config('auth.guards') as $guard => $temp) {
            if (Auth::guard($guard)->attempt($credentials)) {
                $request->session()->regenerate();
                return (new UserInfoController())(Auth::guard($guard)->user());//call userInfoController to sent the user Info
            }
        }

        return response()->json([
            'message' => 'Invalid email or password',
            "errors" => ["credentials" => ['invalid.credentials']]
        ], 422);
    }
}
