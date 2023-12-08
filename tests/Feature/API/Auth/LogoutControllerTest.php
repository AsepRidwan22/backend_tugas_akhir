<?php

namespace Tests\Feature\API\Auth;

use Illuminate\Support\Facades\Hash; // Add this import
use Tests\TestCase;
use App\Models\User; // Add this import
use Illuminate\Foundation\Testing\WithFaker;

class LogoutControllersTest extends TestCase
{
    use WithFaker; // Include the DatabaseTransactions trait

    protected $dokter;
    protected $pasien;

    public function setUp(): void
    {
        parent::setUp();

        $this->dokter = $this->createUserWithRole(2);
        $this->pasien = $this->createUserWithRole(3);
    }

    private function createUserWithRole($roleId)
    {
        return User::create([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => Hash::make('password'),
            'id_role' => $roleId,
        ]);
    }

    public function testPasienSuccessfulLogout()
    {
        // Log in the user and get the token
        $response = $this->postJson('/api/pasien/auth/login', [
            'email' => $this->pasien->email,
            'password' => 'password',
        ]);

        $token = $response->json('access_token');

        // Make a request to the logout endpoint with the token
        $response = $this->json('POST', '/api/auth/logout', [], ['Authorization' => "Bearer $token"]);

        // Assert response
        $response->assertSuccessful()
            ->assertJson([
                'success' => true,
                'message' => 'Logout berhasil',
            ]);
    }

    public function testDokterSuccessfulLogout()
    {
        // Log in the user and get the token
        $response = $this->postJson('/api/dokter/auth/login', [
            'email' => $this->dokter->email,
            'password' => 'password',
        ]);

        $token = $response->json('access_token');

        // Make a request to the logout endpoint with the token
        $response = $this->json('POST', '/api/auth/logout', [], ['Authorization' => "Bearer $token"]);

        // Assert response
        $response->assertSuccessful()
            ->assertJson([
                'success' => true,
                'message' => 'Logout berhasil',
            ]);
    }

    public function testFailedLogout()
    {
        // Log in the user and get the token
        $response = $this->postJson('/api/pasien/auth/login', [
            'email' => $this->pasien->email,
            'password' => 'password',
        ]);

        $token = $response->json('access_token') . 'x';

        $response = $this->json('POST', '/api/auth/logout', [], ['Authorization' => "Bearer $token"]);

        // Assert response status
        $response->assertStatus(401);

        // Assert response structure
        $response->assertJson([
            'message' => 'Your token is invalid. Please, login again.',
            'success' => false,
        ]);
    }

    public function testLogoutWithoutToken()
    {
        // Make a request to the logout endpoint without providing token
        $response = $this->json('POST', '/api/auth/logout');

        // Assert response status
        $response->assertStatus(401);

        // Assert response structure
        $response->assertJson([
            'success' => false,
            'message' => 'Please, attach a Bearer Token to your request',
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        if ($this->dokter) {
            $this->dokter->delete();
        }

        if ($this->pasien) {
            $this->pasien->delete();
        }
    }
}
