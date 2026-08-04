<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $service)
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
            ]
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $result = $this->service->login($credentials);

        if (!$result) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        return response()->json($result);
    }

    public function logout(Request $request)
    {

        $this->service->logout($request->user());

        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }

    public function refresh(Request $request)
    {
        $result = $this->service->refresh($request->user());

        return response()->json($result);
    }

    public function me()
    {
        $user = $this->service->me(auth()->id());

        return response()->json($user);
    }
}
