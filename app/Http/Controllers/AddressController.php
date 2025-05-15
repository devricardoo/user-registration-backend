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

    public function createaddress(Request $request)
    {
        $rules = [
            'public_place' => 'required',
            'cep' => 'required|regex:/^\d{5}-?\d{3}$/|max:8',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'max' => 'O campo :attribute precisa ter no máximo :max caracteres',
            'regex' => 'Informe um CEP válido',
        ];

        $request->validate($rules, $feedback);

        $address = Address::create([
            'public_place' => $request->public_place,
            'cep' => $request->cep,
        ]);

        return response()->json(['address' => $address], 200);
    }

    public function updateaddress(Request $request, $id)
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
            $request->validate($rulesDinamic, $address->feedback());
        } else {
            $request->validate($address->rules(), $address->feedback());
        }
        $address->update($request->all());

        return response()->json(['address' => $address], 200);
    }
}
