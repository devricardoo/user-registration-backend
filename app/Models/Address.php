<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = ['public_place', 'cep'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'addresses_users');
    }

    public function rules()
    {
        return [
            'public_place' => 'required',
            'cep' => 'required|regex:/^\d{5}-?\d{3}$/|max:8',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'max' => 'O campo :attribute precisa ter no máximo :max caracteres',
            'regex' => 'Informe um CEP válido',
        ];
    }
}
