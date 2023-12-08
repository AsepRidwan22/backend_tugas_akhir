<?php

namespace Tests\Unit\API\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase
{
    use WithFaker;

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

    public function testDokterLoginSuccess()
    {
        $response = $this->postJson('/api/dokter/auth/login', [
            'email' => $this->dokter->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Pengguna berhasil masuk',
            ]);
    }

    public function testPasienLoginSuccess()
    {
        $response = $this->postJson('/api/pasien/auth/login', [
            'email' => $this->pasien->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Pengguna berhasil masuk',
            ]);
    }

    public function testInvalidLogin()
    {
        $response = $this->postJson('/api/dokter/auth/login', [
            'email' => $this->dokter->email,
            'password' => 'passwords', // Password salah
        ]);

        $response->assertStatus(400) // Unauthorized
            ->assertJson([
                'success' => false,
                'message' => 'Kredensial tidak valid',
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
