<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'token',
    ];

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'profile_id',
        'token',
    ];

    protected $casts = [
        'profile_id' => 'integer',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'addresses_users');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users,email,' . $this->id,
            'password' => 'required|string|min:6|confirmed',
            'cpf' => 'required|string|max:11|unique:users,cpf,' . $this->id,
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
