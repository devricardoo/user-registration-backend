<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_creates_a_token_for_valid_credentials(): void
    {
        $user = $this->createUser();

        $result = $this->app->make(AuthService::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result['token']);
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_returns_null_for_invalid_credentials(): void
    {
        $user = $this->createUser();

        $result = $this->app->make(AuthService::class)->login([
            'email' => $user->email,
            'password' => 'senha-incorreta',
        ]);

        $this->assertNull($result);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_refresh_revokes_the_current_token_and_creates_a_new_one(): void
    {
        $user = $this->createUser();
        $login = $this->app->make(AuthService::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $oldToken = $login['token'];

        $response = $this
            ->withToken($oldToken)
            ->postJson('/api/auth/refresh');

        $response
            ->assertOk()
            ->assertJsonStructure(['token']);

        $newToken = $response->json('token');

        $this->assertNotSame($oldToken, $newToken);
        $this->assertNull(PersonalAccessToken::findToken($oldToken));
        $this->assertNotNull(PersonalAccessToken::findToken($newToken));
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    private function createUser(): User
    {
        $profile = Profile::create(['name' => 'Teste']);

        return User::create([
            'name' => 'Usuário de teste',
            'email' => 'usuario@example.com',
            'password' => Hash::make('password'),
            'cpf' => '12345678901',
            'profile_id' => $profile->id,
        ]);
    }
}
