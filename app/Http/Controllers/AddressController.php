<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Address;

class AddressController extends Controller
{
    private $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function index()
    {
        $address = Address::all();
        return response()->json(['address' => $address], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'public_place' => 'required|max:100|unique:addresses,public_place',
            'cep' => 'required|regex:/^\d{5}-?\d{3}$/|max:8',
            'users' => 'array',
            'users.*' => 'exists:users,id',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'max' => 'O campo :attribute precisa ter no máximo :max caracteres',
            'regex' => 'Informe um CEP válido',
            'exists' => 'Esse usuário não existe',
            'unique' => 'Esse endereço já foi cadastrado',
        ];

        $request->validate($rules, $feedback);

        $address = Address::create([
            'public_place' => $request->public_place,
            'cep' => $request->cep,
        ]);

        //associa o endereço aos usuários
        if ($request->has('users')) {
            $address->users()->attach($request->users);
        }

        return response()->json(['msg' => 'Endereço cadastrado com sucesso!'], 200);
    }

    public function update(Request $request, $id)
    {
        $address = $this->address->find($id);

        if ($address == null) {
            return response()->json(['msg' => 'Endereço não encontrado'], 404);
        }

        if ($request->method() === 'PATCH') {
            $rulesDinamic = array();
            foreach ($address->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $rulesDinamic[$input] = $rule;
                }
            }
            if ($request->has('users')) {
                $rulesDinamic['users'] = 'array';
                $rulesDinamic['users.*'] = 'exists:users,id';
            }

            $request->validate($rulesDinamic, $address->feedback());
        } else {

            $rules = $address->rules();

            if ($request->has('users')) {
                $rules['users'] = 'array';
                $rules['users.*'] = 'exists:users,id';
            }

            $request->validate($rules, $address->feedback());
        }
        $address->update($request->all());

        if ($request->has('users')) {
            $address->users()->sync($request->users);
        }

        return response()->json(['address' => $address->load('users')], 200);
    }

    public function delete($id)
    {
        return response()->json(['msg' => 'Endereço deletado com sucesso'], 200);
    }
}
