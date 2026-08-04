<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('cep')) {
            $this->merge([
                'cep' => preg_replace('/\D/', '', (string) $this->input('cep')),
            ]);
        }
    }

    public function rules(): array
    {
        $presence = $this->isMethod('put') ? 'required' : 'sometimes';

        return [
            'public_place' => [
                $presence,
                'string',
                'max:100',
                Rule::unique('addresses', 'public_place')->ignore($this->route('id')),
            ],
            'cep' => [$presence, 'regex:/^\d{8}$/'],
            'neighborhood' => [$presence, 'string', 'max:100'],
            'city' => [$presence, 'string', 'max:50'],
            'state' => [$presence, 'string', 'size:2'],
            'number' => ['sometimes', 'nullable', 'string', 'max:10'],
            'complement' => ['sometimes', 'nullable', 'string', 'max:100'],
            'users' => ['sometimes', 'array'],
            'users.*' => ['integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'max' => 'O campo :attribute precisa ter no máximo :max caracteres.',
            'size' => 'O campo :attribute precisa ter exatamente :size caracteres.',
            'regex' => 'Informe um CEP válido.',
            'exists' => 'Esse usuário não existe.',
            'unique' => 'Esse endereço já foi cadastrado.',
        ];
    }
}
