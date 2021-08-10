<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // autenticação por email e senha
        $credentials = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'credentials not valid'], 403);
        }
        return response()->json(['token' => $token], 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['token' => 'token has been revoked'], 200);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token], 200);

    }

    public function me()
    {
        return response()->json(auth()->user(), 200);
    }
}
