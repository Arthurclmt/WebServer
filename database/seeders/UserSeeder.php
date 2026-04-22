<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //Utilisateur normal
        User::create([
            'pseudo' => 'User Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'simple', 
        ]);

        //Admin
        User::create([
            'pseudo' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin', 
        ]);
    }
}