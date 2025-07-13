<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'FeelingWeb',
            'email' => 'contato@feelingweb.com.br',
            'email_verified_at' => now(),
            'password' => Hash::make('a1s2d3f4'),
        ]);
    }
} 