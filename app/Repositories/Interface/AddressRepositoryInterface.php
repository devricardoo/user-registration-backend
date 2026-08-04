<?php

namespace App\Repositories\Interface;

use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AddressRepositoryInterface
{
  public function index(array $data): LengthAwarePaginator;

  public function create(array $data): Address;

  public function findById(int $id): ?Address;

  public function searchByCep(string $cep): Collection;

  public function update(Address $address, array $data): Address;

  public function delete(Address $address): void;
}
