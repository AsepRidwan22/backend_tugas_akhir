<?php

namespace Tests\Feature\API\Pasien;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class IdentitasPasienControllerTest extends TestCase
{
    use WithFaker;

    protected $pasien;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->pasien = $this->createUserWithRole(3);
        $this->token = $this->getToken();
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

    private function getToken()
    {
        $response = $this->postJson('/api/pasien/auth/login', [
            'email' => $this->pasien->email,
            'password' => 'password',
        ]);

        return $response->json('access_token');
    }

    private function createIdentitasPasien($params)
    {
        return $this->json('POST', '/api/pasien/identitas/store', $params, ['Authorization' => "Bearer $this->token"]);
    }

    public function testCreateIdentitasPasienSuccess()
    {
        $params = [
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numberBetween(2, 9) . $this->faker->randomNumber(8, true),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
            'foto' => base64_encode(file_get_contents('https://picsum.photos/200/300')),
        ];

        // Create identitas
        $createResponse = $this->createIdentitasPasien($params);

        // Assert response
        $createResponse->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    // Expected data structure
                ],
            ]);
    }

    public function testCreateIdentitasPasienWithoutFotoSuccess()
    {

        $params = [
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numberBetween(2, 9) . $this->faker->randomNumber(8, true),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
        ];

        // Create identitas
        $createResponse = $this->createIdentitasPasien($params);

        // Assert response
        $createResponse->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    // Expected data structure
                ],
            ]);
    }

    public function testUpdateIdentitasPasienSuccess()
    {
        // Create identitas data
        $createParams = [
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numberBetween(2, 9) . $this->faker->randomNumber(8, true),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
            'foto' => base64_encode(file_get_contents('https://picsum.photos/200/300')),
        ];

        $this->createIdentitasPasien($createParams);

        // Update parameters
        $updateParams = [
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numberBetween(2, 9) . $this->faker->randomNumber(8, true),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
            'foto' => base64_encode(file_get_contents('https://picsum.photos/200/300')),
        ];

        // Update identitas
        $updateResponse = $this->json('PUT', "/api/pasien/identitas", $updateParams, ['Authorization' => "Bearer $this->token"]);

        // Assert response
        $updateResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    // Expected data structure
                ],
            ]);
    }

    public function testUpdateIdentitasPasienWithoutFotoSuccess()
    {
        // Create identitas data
        $createParams = [
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numberBetween(2, 9) . $this->faker->randomNumber(8, true),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
            'foto' => base64_encode(file_get_contents('https://picsum.photos/200/300')),
        ];

        $this->createIdentitasPasien($createParams);

        // Update parameters
        $updateParams = [
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numberBetween(2, 9) . $this->faker->randomNumber(8, true),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
        ];

        // Update identitas
        $updateResponse = $this->json('PUT', "/api/pasien/identitas", $updateParams, ['Authorization' => "Bearer $this->token"]);

        // Assert response
        $updateResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    // Expected data structure
                ],
            ]);
    }

    public function testGetIdentitasPasienSuccess()
    {
        $createParams = [
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat' => $this->faker->address,
            'telepon' => '08' . $this->faker->numberBetween(2, 9) . $this->faker->randomNumber(8, true),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
            'foto' => base64_encode(file_get_contents('https://picsum.photos/200/300')),
        ];

        $this->createIdentitasPasien($createParams);

        // Make a request to delete identitas
        $response = $this->json('GET', "/api/pasien/identitas", [], ['Authorization' => "Bearer $this->token"]);

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    // Expected data structure
                ],
            ]);
    }

    public function tearDown(): void
    {
        $this->pasien->delete();
    }
}
