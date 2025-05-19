<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = ['public_place', 'cep', 'neighborhood', 'city', 'state', 'number', 'complement'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'addresses_users');
    }

    public function rules()
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

    public function feedback()
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