<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

class AddressController extends Controller
{
    private $address;

    public function __construct(Address $address)
    {
        $this->middleware('auth:api');
        $this->address = $address;
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);


        $perPage = $validated['per_page'] ?? 5;
        $page = $validated['page'] ?? 1;

        $addresses = Address::with(['users',])
            ->paginate($perPage);


        // Adds 'per_page' and 'page' parameters to pagination response to make frontend navigation easier
        $addresses->appends($request->all());

        return response()->json($addresses, 200);
    }

    public function searchByCep($cep)
    {
        $address = Address::where('cep', $cep)->first();

        if (!$address) {
            return response()->json(['message' => 'Endereço não encontrado.'], 404);
        }

        return response()->json($address);
    }

    public function show($id)
    {
        $address = $this->address->find($id);
        if (!$address) {
            return response()->json(['error' => 'Endereço não encontrado'], 404);
        }
        return response()->json(['address' => $address], 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->address->rules(), $this->address->feedback());

        $address = Address::create([
            'public_place' => $request->public_place,
            'cep' => $request->cep,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => $request->state,
            'number' => $request->number,
            'complement' => $request->complement
        ]);

        //associates the address with the users
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

        return response()->json(['msg' => 'Endereço atualizado com sucesso'], 200);
    }

    public function delete($id)
    {
        $authUser = Auth::user();
        $address = $this->address->find($id);
        if (!$authUser) {
            return response()->json(['msg' => 'Usuário não está logado '], 404);
        }

        if (!$address) {
            return response()->json(['msg' => 'Endereço não encontrado'], 404);
        }

        $address->delete();

        return response()->json(['msg' => 'Endereço deletado com sucesso'], 200);
    }
}
