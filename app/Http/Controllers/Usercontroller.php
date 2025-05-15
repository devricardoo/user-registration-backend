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


    public function index()
    {
        $user = User::all();
        return response()->json($user, 200);
    }

    public function createprofile(Request $request)
    {
        $profile = Profile::create($request->all());
        return response()->json($profile, 200);
    }

    public function update(Request $request, $id)
    {
        $user = $this->user->find($id);

        if ($user == null) {
            return response()->json(['msg' => 'Este usuário não foi encontrado'], 404);
        }

        if ($request->method() === 'PATCH') {
            $rulesDinamic = array();
            foreach ($user->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $rulesDinamic[$input] = $rule;
                }
            }
            $request->validate($rulesDinamic, $user->feedback());
        } else {
            $request->validate($user->rules(), $user->feedback());
        }
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['msg' => 'Usuário deletado com sucesso'], 200);
    }
}
