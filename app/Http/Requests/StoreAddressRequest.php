<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'public_place' => 'required|max:100|unique:addresses,public_place',
            'cep' => 'required|regex:/^\d{5}-?\d{3}$/|max:8',
            'users' => 'array',
            'users.*' => 'exists:users,id',
            'neighborhood' => 'required|max:100',
            'city' => 'required|max:50',
            'state' => 'required|max:2',
            'number' => 'max:10',
            'complement' => 'max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'max' => 'O campo :attribute precisa ter no máximo :max caracteres',
            'regex' => 'Informe um CEP válido',
            'exists' => 'Esse usuário não existe',
            'unique' => 'Esse endereço já foi cadastrado',
        ];
    }
}
