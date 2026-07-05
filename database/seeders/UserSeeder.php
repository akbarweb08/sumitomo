<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Sumitomo',
            'email' => 'admin@sumitomo.test',
            'password' => 'admin123',
            'nama' => 'admin',
            'role' => 'admin',
            'permit' => 'super'
        ]);

        \App\Models\User::create([
            'name' => 'User GRACE',
            'email' => 'grace@sumitomo.test',
            'password' => 'grace123',
            'nama' => 'User Grace',
            'role' => 'user',
            'permit' => 'GRACE'
        ]);
        
        \App\Models\User::create([
            'name' => 'User 206',
            'email' => '206@sumitomo.test',
            'password' => '206pass',
            'nama' => 'User 206',
            'role' => 'user',
            'permit' => '206'
        ]);
    }
}
