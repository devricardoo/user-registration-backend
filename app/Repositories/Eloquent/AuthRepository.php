<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interface\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
  public function __construct(
    private readonly User $entity
  ) {}

  public function findByEmail(string $email): ?User
  {
    return $this->entity
      ->where('email', $email)
      ->first();
  }

  public function createToken(User $user): string
  {
    return $user->createToken('auth_token')->plainTextToken;
  }

  public function deleteCurrentToken(User $user): void
  {
    $user->currentAccessToken()?->delete();
  }

  public function findAuthenticatedUser(int $id): ?User
  {
    return $this->entity
      ->with(['profile', 'addresses'])
      ->find($id);
  }
}
