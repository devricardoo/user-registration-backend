<?php

namespace App\Repositories\Eloquent;

use App\Models\Profile;
use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
  public function __construct(public readonly User $entity) {}

  public function index(array $data)
  {
    $perPage = $data['per_page'] ?? 5;

    return $this->entity->with(['profile', 'addresses'])
      ->paginate($perPage);
  }

  public function createprofile(array $data): Profile
  {
    return Profile::create($data);
  }

  public function create(array $data): User
  {
    $user = $this->entity->create([
      'name'       => $data['name'],
      'email'      => $data['email'],
      'password'   => Hash::make($data['password']),
      'cpf'        => $data['cpf'],
      'profile_id' => $data['profile_id'],
    ]);
    return $user;
  }

  public function show(int $id): User
  {
    return $this->entity::with('addresses')->find($id);
  }

  public function findById(int $id): User
  {
    return $this->entity::find($id);
  }

  public function update(User $entity, array $data): User
  {
    $entity->update($data);
    return $entity;
  }

  public function delete(User $entity)
  {
    $entity->delete();
  }
}