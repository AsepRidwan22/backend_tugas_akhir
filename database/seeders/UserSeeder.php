<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => Str::uuid()->toString(),
                'username' => 'Admin1',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'id_role' => 1,
                'status' => true,
                'email_verified_at' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'username' => 'Dokter1',
                'email' => 'dokter@gmail.com',
                'password' => Hash::make('password'),
                'id_role' => 2,
                'status' => true,
                'email_verified_at' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'username' => 'Pasien1',
                'email' => 'pasien@gmail.com',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'status' => true,
                'email_verified_at' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
        ]);
    }
}
