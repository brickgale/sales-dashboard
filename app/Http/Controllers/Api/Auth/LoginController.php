<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Rules\Password::defaults()],
            'remember' => ['boolean'],
        ]);

        $remember = $request->boolean('remember', false);
        $attempt = Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ], $remember);

        if ($attempt) {
            $user = Auth::user();
            $token = $user->createToken('API Token', ['*'], now()->addDays($remember ? 30 : 1));
            
            return response()->json([
                'user' => $user, 
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at,
                'remember' => $remember
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function logoutAll(Request $request)
    {
        // Delete all tokens for the user (logout from all devices)
        $request->user()->tokens()->delete();
        
        return response()->json(['message' => 'Logged out from all devices successfully']);
    }
}