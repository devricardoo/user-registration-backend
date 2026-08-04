<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Repositories\Interface\AddressRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository implements AddressRepositoryInterface
{
  public function __construct(public readonly Address $entity) {}

  public function index(array $data): LengthAwarePaginator
  {

    $perPage = $data['per_page'] ?? 5;

    return $this->entity
      ->with('users')
      ->paginate($perPage);
  }

  public function create(array $data): Address
  {
    return $this->entity->create($data);
  }

  public function findById(int $id): ?Address
  {
    return $this->entity->find($id);
  }

  public function searchByCep(string $cep): Collection
  {
    return $this->entity
      ->with('users')
      ->where('cep', $cep)
      ->get();
  }

  public function update(Address $address, array $data): Address
  {
    $address->update($data);

    return $address;
  }

  public function delete(Address $address): void
  {
    $address->delete();
  }
}
