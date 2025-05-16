<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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

    public function unauthorized()
    {
        return response()->json([
            'error' => 'Unauthorized'
        ]);
    }

    public function create(Request $request)
    {
        $request->validate($this->user->rules(), $this->user->feedback());


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'cpf' => $request->cpf,
            'profile_id' => $request->profile_id
        ]);

        if ($request->has('addresses')) {
            $user->addresses()->attach($request->addresses);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = Auth::attempt($credentials)) {
            return response()->json([
                'token' => $token,
            ], 200);
        } else {
            return response()->json(['error' => 'E-mail ou senha inválidos'], 401);
        }
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
}
