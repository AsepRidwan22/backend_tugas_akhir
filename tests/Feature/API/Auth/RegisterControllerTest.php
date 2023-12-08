<?php

namespace Tests\Feature\API\Auth;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterControllerTest extends TestCase
{
    use WithFaker;

    protected $email;
    protected $password;

    public function setUp(): void
    {
        parent::setUp();

        $this->email = $this->faker->safeEmail;
        $this->password = $this->faker->password;
    }

    private function registerPasien($params)
    {
        return $this->postJson('/api/pasien/auth/register', $params);
    }

    public function testPasienRegisterSuccess()
    {
        $params = [
            'username' => $this->faker->userName,
            'email' => $this->email,
            'password' => $this->password,
        ];

        $response = $this->registerPasien($params);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'username',
                        'email',
                        'id_role',
                    ],
                    'token',
                ],
            ]);
    }

    public function testInvalidEmail()
    {
        $params = [
            'username' => $this->faker->userName,
            'email' => 'invalid_email',
            'password' => $this->password,
        ];

        $response = $this->registerPasien($params);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testDuplicateEmail()
    {
        User::create([
            'username' => $this->email,
            'email' => $this->email,
            'password' => Hash::make('password'),
            'id_role' => 3,
        ]);

        $params = [
            'username' => $this->email,
            'email' => $this->email,
            'password' => 'password',
        ];

        $response = $this->registerPasien($params);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testInvalidPassword()
    {
        $params = [
            'username' => $this->faker->userName,
            'email' => $this->email,
            'password' => 'pass',
        ];

        $response = $this->registerPasien($params);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'password',
                ],
            ]);
    }

    public function testInvalidUsername()
    {
        $params = [
            'username' => 'pasi',
            'email' => $this->email,
            'password' => $this->password,
        ];

        $response = $this->registerPasien($params);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'username',
                ],
            ]);
    }

    public function tearDown(): void
    {

        if ($this->email) {
            User::where('email', $this->email)->delete();
        }
    }
}
