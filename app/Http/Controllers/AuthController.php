<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'create',
                'unauthorized',
                'user',
            ]
        ]);

        $this->user = $user;
    }

    public function logout()
    {
        $user = auth()->check();
        if ($user) {
            auth()->logout();
            return response()->json(['msg' => 'Logout realizado com sucesso'], 200);
        } else {
            return response()->json(['error' => 'Usuário não logado'], 401);
        }
    }

    public function refresh()
    {
        $newToken = JWTAuth::parseToken()->refresh();

        return response()->json([
            'token' => $newToken,
        ]);
    }

    public function me()
    {
        return response()->json(User::with('profile')->find(auth()->id()));
    }
}