<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    $user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
      return response()->json([
        'error' => 'Credenciais inválidas'
      ], 401);
    }

    $user->last_login_at = now();
    $user->save();

    $token = $user->createToken('auth_token')->plainTextToken;

    return [
      'token' => $token
    ];
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
      $requiredKeys = ['public_place', 'cep', 'neighborhood', 'city', 'state'];

      if (!empty(array_diff($requiredKeys, array_keys($address)))) {
        return null; // endereço incompleto
      }

      $newAddress = Address::firstOrCreate([
        'public_place' => $address['public_place'],
        'cep'          => $address['cep'],
        'neighborhood' => $address['neighborhood'],
        'city'         => $address['city'],
        'state'        => $address['state'],
        'number'       => $address['number'] ?? null,
        'complement'   => $address['complement'] ?? null,
      ]);

      return $newAddress->id;
    }

    return null;
  }

  public function show(int $id): User
  {
    $user = $this->repository->show($id);

    if ($user === null) {
      throw new NotFoundHttpException('Usuário não encontrado.');
    }

    return $user;
  }

  public function findById(int $id): User
  {
    return $this->show($id);
  }

  public function update(int $id, array $data): User
  {
    return DB::transaction(function () use ($id, $data) {
      $user = $this->show($id);
      $addresses = $data['addresses'] ?? null;

      unset($data['addresses'], $data['password_confirmation']);

      if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
      } else {
        unset($data['password']);
      }

      // Atualiza os dados principais
      $this->repository->update($user, $data);

      // Se vierem endereços
      if (is_array($addresses)) {
        foreach ($addresses as $index => $addressData) {
          if (isset($addressData['id'])) {
            // Atualiza endereço existente
            $address = $user->addresses()->find($addressData['id']);

            if (!$address) {
              throw ValidationException::withMessages([
                "addresses.$index.id" => ['Endereço não pertence ao usuário.'],
              ]);
            }

            $address->update($addressData);
          } else {
            // Reutiliza um endereço idêntico e evita vínculos duplicados
            $addressId = $this->resolveAddressId($addressData);

            if (!$addressId) {
              throw ValidationException::withMessages([
                "addresses.$index" => ['Endereço incompleto.'],
              ]);
            }

            $user->addresses()->syncWithoutDetaching($addressId);
          }
        }
      }

      return $user->load('profile', 'addresses');
    });
  }

  public function delete(int $id): void
  {
    DB::transaction(function () use ($id) {
      $user = $this->repository->findById($id);

      if (!$user) {
        throw new NotFoundHttpException('Usuário não encontrado.');
      }

      $addresses = $user->addresses()->get();

      $this->repository->delete($user);

      foreach ($addresses as $address) {
        if (!$address->users()->exists()) {
          $address->delete();
        }
      }
    });
  }

  public function search(array $data)
  {
    return $this->repository->search($data);
  }
}
