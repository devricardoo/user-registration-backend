<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;

class Usercontroller extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);


        $perPage = $validated['per_page'] ?? 5;
        $page = $validated['page'] ?? 1;

        // Recupera os usuários paginados com as relações 'profile' e 'addresses'
        $users = User::with(['profile', 'addresses'])
            ->paginate($perPage);

        // Adiciona os parâmetros 'per_page' e 'page' à resposta de paginação para facilitar a navegação no frontend
        $users->appends($request->all());

        return response()->json($users, 200);
    }


    public function createprofile(Request $request)
    {
        $profile = Profile::create($request->all());
        return response()->json($profile, 200);
    }

    public function show($id)
    {
        $user = User::with(['profile', 'addresses'])->find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = $this->user->find($id);

        if ($user == null) {
            return response()->json(['error' => 'Este usuário não foi encontrado'], 404);
        }

        if ($request->method() === 'PATCH') {
            $rulesDinamic = array();
            foreach ($user->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    if ($input == 'password') {
                        $rulesDinamic[$input] = 'confirmed|string|min:4';
                    } else {
                        $rulesDinamic[$input] = $rule;
                    }
                }
            }
            if ($request->has('addresses')) {
                $rulesDinamic['addresses'] = 'array';
                $rulesDinamic['addresses.*'] = 'exists:addresses,id';
            }

            $request->validate($rulesDinamic, $user->feedback());
        } else {
            $rules = $user->rules();

            if ($request->has('addresses')) {
                $rules['addresses'] = 'array';
                $rules['addresses.*'] = 'exists:addresses,id';
            }
            $request->validate($user->rules(), $user->feedback());
        }
        $user->update($request->all());

        if ($request->has('addresses')) {
            $user->addresses()->sync($request->addresses);
        }
        return response()->json($user->load('addresses'), 200);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $auth = Auth::user();
        if ($auth->id == $user->id) {
            return response()->json(['error' => 'Usuário logado não pode ser deletado'], 401);
        }
        $user->delete();

        return response()->json(['msg' => 'Usuário deletado com sucesso'], 200);
    }
}
