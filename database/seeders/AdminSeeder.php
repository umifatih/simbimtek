<?php

// database/seeders/AdminSeeder.php
namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['name' => 'Admin A', 'email' => 'admina@simbimtek.com', 'password' => 'ubahsegera1'],
            ['name' => 'Admin B', 'email' => 'adminb@simbimtek.com', 'password' => 'ubahsegera2'],
            ['name' => 'Admin C', 'email' => 'adminc@simbimtek.com', 'password' => 'ubahsegera3'],
        ];

        foreach ($admins as $data) {
            Admin::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make($data['password'])]
            );
        }
    }
}