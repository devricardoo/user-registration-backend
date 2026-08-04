<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interface\AuthRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthService
{
  public function __construct(
    private readonly AuthRepositoryInterface $repository
  ) {}

  public function login(array $credentials): ?array
  {
    $user = $this->repository->findByEmail($credentials['email']);

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      return null;
    }

    return [
      'token' => $this->repository->createToken($user),
    ];
  }

  public function logout(User $user): void
  {
    $this->repository->deleteCurrentToken($user);
  }

  public function refresh(User $user): array
  {
    return DB::transaction(function () use ($user) {
      $this->repository->deleteCurrentToken($user);

      return [
        'token' => $this->repository->createToken($user),
      ];
    });
  }

  public function me(int $id): User
  {
    $user = $this->repository->findAuthenticatedUser($id);

    if (!$user) {
      throw new NotFoundHttpException('Usuário não encontrado.');
    }

    return $user;
  }
}
