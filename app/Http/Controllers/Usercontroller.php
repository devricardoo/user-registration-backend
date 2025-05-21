<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

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

        $users = User::with(['profile', 'addresses'])
            ->paginate($perPage);

        // Adds 'per_page' and 'page' parameters to pagination response to make frontend navigation easier
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
            $rulesDinamic = [];
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
                foreach ($user->rules() as $input => $rule) {
                    if (str_starts_with($input, 'addresses')) {
                        $rulesDinamic[$input] = $rule;
                    }
                }
            }

            $request->validate($rulesDinamic, $user->feedback());
        } else {
            $request->validate($user->rules(), $user->feedback());
        }

        $data = $request->all();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);


        if ($request->has('addresses')) {
            foreach ($request->addresses as $addressData) {
                if (isset($addressData['id'])) {
                    $address = Address::find($addressData['id']);
                    if (!$address || !$user->addresses->contains($address->id)) {
                        return response()->json(['error' => 'Endereço não pertence ao usuário'], 403);
                    }
                    $address->update($addressData);
                } else {
                    // Creates new address linked to the user
                    $user->addresses()->create($addressData);
                }
            }
        }

        return response()->json($user->load('profile', 'addresses'), 200);
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


        $user->addresses()->detach();

        foreach ($user->addresses as $address) {
            if ($address->users()->count() === 0) {
                $address->delete();
            }
        }

        $user->delete();

        return response()->json(['msg' => 'Usuário deletado com sucesso'], 200);
    }
}
