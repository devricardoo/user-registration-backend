<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
  public function __construct(private readonly UserRepositoryInterface $repository, public User $entity) {}

  public function index(array $data)
  {
    return $this->repository->index($data);
  }

  public function createprofile(array $data)
  {
    return $this->repository->createprofile($data);
  }

  public function login(array $data)
  {
    if (!$token = Auth::attempt($data)) {
      return response()->json([
        'error' => 'Credenciais inválidas'
      ], 401);
    }

    return response()->json([
      'token' => $token,
    ]);
  }

  public function create(array $data): User
  {
    return DB::transaction(function () use ($data) {
      $user = $this->repository->create($data);

      if (!empty($data['addresses']) && is_array($data['addresses'])) {
        $this->attachAddressesToUser($user, $data['addresses']);
      }

      return $user->load('addresses');
    });
  }

  /**
   * Associa endereços a um usuário (IDs existentes ou novos)
   */
  private function attachAddressesToUser(User $user, array $addresses): void
  {
    foreach ($addresses as $address) {
      $addressId = $this->resolveAddressId($address);
      if ($addressId) {
        $user->addresses()->syncWithoutDetaching($addressId);
      } else {
        Log::warning('Endereço inválido ao criar usuário:', ['address' => $address]);
      }
    }
  }

  /**
   * Retorna o ID do endereço, seja existente ou criado.
   */
  private function resolveAddressId(mixed $address): ?int
  {
    if (is_int($address)) {
      return $address; // ID existente
    }

    if (is_array($address)) {
      $requiredKeys = ['public_place', 'cep', 'neighborhood', 'city', 'state', 'number', 'complement'];

      if (!empty(array_diff($requiredKeys, array_keys($address)))) {
        return null; // endereço incompleto
      }

      $newAddress = Address::firstOrCreate([
        'public_place' => $address['public_place'],
        'cep'          => $address['cep'],
        'neighborhood' => $address['neighborhood'],
        'city'         => $address['city'],
        'state'        => $address['state'],
        'number'       => $address['number'],
        'complement'   => $address['complement'],
      ]);

      return $newAddress->id;
    }

    return null;
  }

  public function show(int $id): User
  {
    return $this->repository->show($id);
  }

  public function findById($id): User
  {
    return $this->repository->show($id);
  }


  public function update(int $id, array $data): User
  {
    return DB::transaction(function () use ($id, $data) {
      $user = $this->repository->show($id);

      if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
      } else {
        unset($data['password']);
      }

      // Atualiza os dados principais
      $this->repository->update($user, $data);

      // Se vierem endereços
      if (isset($data['addresses']) && is_array($data['addresses'])) {
        foreach ($data['addresses'] as $addressData) {
          if (isset($addressData['id'])) {
            // Atualiza endereço existente
            $address = Address::find($addressData['id']);
            if (!$address || !$user->addresses->contains($address->id)) {
              throw new \Exception('Endereço não pertence ao usuário.');
            }
            $address->update($addressData);
          } else {
            // Cria novo endereço vinculado ao usuário
            $user->addresses()->create($addressData);
          }
        }
      }

      return $user->load('profile', 'addresses');
    });
  }

  public function delete(int $id)
  {
    $entity = $this->repository->findById($id);

    if (!$entity) {
      return response()->json(['error' => 'Usuário não encontrado'], 404);
    }

    $entity->addresses()->detach();

    foreach ($entity->addresses as $address) {
      if ($address->users()->count() === 0) {
        $address->delete();
      }
    }

    $this->repository->delete($entity);

    return response()->json(['msg' => 'Usuário deletado com sucesso']);
  }

  public function search(array $data)
  {
    return $this->repository->search($data);
  }
}