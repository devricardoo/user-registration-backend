<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'profile_id',
    ];

    protected $casts = [
        'profile_id' => 'integer',
        'last_login_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'addresses_users');
    }

    public function rules($id = null)
    {
        return [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users,email,' . $id,
            'password' => 'required|string|min:6|confirmed',
            'cpf' => 'required|string|max:11|unique:users,cpf,' . $id,
            'profile_id' => 'nullable|exists:profiles,id',
            'addresses' => 'sometimes|array',
            'addresses.*.id' => 'sometimes|exists:addresses,id',
            'addresses.*.public_place' => 'nullable|string',
            'addresses.*.cep' => 'nullable|string',
            'addresses.*.neighborhood' => 'nullable|string',
            'addresses.*.city' => 'nullable|string',
            'addresses.*.state' => 'nullable|string',
            'addresses.*.number' => 'nullable|string',
            'addresses.*.complement' => 'nullable|string',

        ];
    }

    public function updateRules(int $id): array
    {
        return [
            'name' => 'sometimes|string|max:100',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'password' => 'sometimes|nullable|string|min:6|confirmed',
            'cpf' => [
                'sometimes',
                'string',
                'max:11',
                Rule::unique('users', 'cpf')->ignore($id),
            ],
            'profile_id' => 'sometimes|integer|exists:profiles,id',
            'addresses' => 'sometimes|array',
            'addresses.*.id' => 'sometimes|integer|exists:addresses,id',
            'addresses.*.public_place' => 'required_without:addresses.*.id|string|max:100',
            'addresses.*.cep' => 'required_without:addresses.*.id|digits:8',
            'addresses.*.neighborhood' => 'required_without:addresses.*.id|string|max:100',
            'addresses.*.city' => 'required_without:addresses.*.id|string|max:50',
            'addresses.*.state' => 'required_without:addresses.*.id|string|size:2',
            'addresses.*.number' => 'nullable|string|max:10',
            'addresses.*.complement' => 'nullable|string|max:100',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'unique' => 'O campo :attribute já existe',
            'email' => 'O campo :attribute precisa ser um email válido',
            'cpf.unique' => 'O campo CPF é inválido',
            'min' => 'O campo :attribute precisa ter no mínimo :min caracteres',
            'exists' => 'Esse perfil não existe',
            'confirmed' => 'As senhas precisam ser iguais',

        ];
    }
}
