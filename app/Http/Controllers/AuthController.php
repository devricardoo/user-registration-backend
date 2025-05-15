<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'create',
                'unauthorized',
                'user',
            ]
        ]);
    }

    public function unauthorized()
    {
        return response()->json([
            'error' => 'Unauthorized'
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:4',
            'cpf' => 'required|string|min:11|unique:users,cpf',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'unique' => 'O campo :attribute já existe',
            'email' => 'O campo :attribute precisa ser um email válido',
            'min' => 'O campo :attribute precisa ter no mínimo :min caracteres',
        ];

        $request->validate($rules, $feedback);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'cpf' => $request->cpf,
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = Auth::attempt($credentials)) {
            return response()->json([
                'token' => $token,
                'msg' => 'Seja bem vindo(a)'
            ], 200);
        } else {
            return response()->json(['error' => 'Usuário ou senha inválidos'], 401);
        }
    }

    public function logout()
    {
        $user = auth()->check();
        if ($user) {
            auth()->logout();
            return response()->json(['msg' => 'Logout realizado com sucesso'], 200);
        } else {
            return response()->json(['msg' => 'Usuário não logado'], 401);
        }
    }
}
