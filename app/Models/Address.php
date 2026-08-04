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
        return $this->belongsToMany(User::class, 'addresses_users')
            ->withPivot('address_id', 'user_id');
    }

}
