<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Malik Bilawal',
            'email' => 'itsz.bilawal@gmail.com',
            'password' => Hash::make('password123'), // secure later
            'role_id' => 13, // super_admin role_id from roles table
        ]);
    }
}