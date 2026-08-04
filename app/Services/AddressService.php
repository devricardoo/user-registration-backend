<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\Interface\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AddressService
{
  public function __construct(
    private readonly AddressRepositoryInterface $repository
  ) {}

  public function index(array $data)
  {
    return $this->repository->index($data);
  }

  public function create(array $data)
  {
    return DB::transaction(function () use ($data) {
      $users = $data['users'] ?? [];

      unset($data['users']);

      $address = $this->repository->create($data);

      if (!empty($users)) {
        $address->users()->attach($users);
      }

      return $address->load('users');
    });
  }

  public function show(int $id): Address
  {
    $address = $this->repository->findById($id);

    if ($address === null) {
      throw new NotFoundHttpException('Endereço não encontrado.');
    }

    return $address;
  }

  public function searchByCep(string $cep): Collection
  {
    $cep = preg_replace('/\D/', '', $cep);

    if (strlen($cep) !== 8) {
      throw ValidationException::withMessages([
        'cep' => ['Informe um CEP válido.'],
      ]);
    }

    return $this->repository->searchByCep($cep);
  }

  public function update(int $id, array $data): Address
  {
    return DB::transaction(function () use ($id, $data) {
      $address = $this->show($id);
      $usersProvided = array_key_exists('users', $data);
      $users = $data['users'] ?? [];

      unset($data['users']);

      $updatedAddress = $this->repository->update($address, $data);

      if ($usersProvided) {
        $updatedAddress->users()->sync($users);
      }

      return $updatedAddress->load('users');
    });
  }

  public function delete(int $id): void
  {
    $address = $this->show($id);
    $this->repository->delete($address);
  }
}
