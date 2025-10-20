<?php

namespace App\Repositories\Interface;

use App\Models\Profile;
use App\Models\User;

interface UserRepositoryInterface
{
  public function index(array $data);

  public function createprofile(array $data): Profile;

  public function create(array $data): User;

  public function show(int $id): User;

  public function findById(int $id): User;

  public function update(User $entity, array $data);

  public function delete(User $entity);
}