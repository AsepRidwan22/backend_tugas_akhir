<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //Seeder untuk Role
        $this->call(RoleSeeder::class);

        //Seeder untuk User
        $this->call(UserSeeder::class);

        //Seeder untuk Identitas Pasien
        // DB::table('identitas_pasiens')->insert([
        //     [
        //         'id' => Str::uuid()->toString(),
        //         'id_user' => 'b1d857a8-8f05-48de-ab0c-9dadfdafee8b',
        //         'nama' => 'Pasien 1',
        //         'tanggal_lahir' => '1999-01-01',
        //         'alamat' => 'Jl. Pasien 1',
        //         'telepon' => '081234567890',
        //         'jenis_kelamin' => 'Laki-laki',
        //         'golongan_darah' => 'A',
        //         'foto' => 'pasien1.jpg',
        //         'created_at' => \Carbon\Carbon::now(),
        //         'updated_at' => \Carbon\Carbon::now()
        //     ],
        // ]);

        //Seeder untuk Kesehatan Pasien
        // DB::table('kesehatan_pasiens')->insert([
        //     [
        //         'id' => Str::uuid()->toString(),
        //         'lama_diabetes' => '2021-01-01',
        //         'tinggi_badan' => 170,
        //         'berat_badan' => 70,
        //         // 'lingkar_lengan_atas' => 30,
        //         // 'lingkar_perut' => 30,
        //         'lingkar_pinggul' => 30,
        //         'lingkar_pinggang' => 30,
        //         'riwayat_keluarga_diabetes' => true,
        //         'perokok' => true,
        //         'riwayat_stroke' => true,
        //         'tekanan_darah_sistolik' => 120,
        //         'tekanan_darah_diastolik' => 80,
        //         'path_rekam_medis' => 'rekam_medis1.jpg',
        //         'id_identitas_pasien' => '12a231f7-dbad-420e-a762-f65d1fb7744c',
        //         'created_at' => \Carbon\Carbon::now(),
        //         'updated_at' => \Carbon\Carbon::now()
        //     ],
        // ]);
    }
}
