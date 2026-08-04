<?php

namespace App\Repositories\Interface;

use App\Models\User;

interface AuthRepositoryInterface
{
  public function findByEmail(string $email): ?User;

  public function createToken(User $user): string;

  public function deleteCurrentToken(User $user): void;

  public function findAuthenticatedUser(int $id): ?User;
}
