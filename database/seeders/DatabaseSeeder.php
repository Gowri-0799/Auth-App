<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Admin::factory()->create([
            'admin_name' => 'admin',
            'email' => 't6847292@gmail.com',
            'password' => 'Socxo@123',
            'role'=>'Super Admin'
        ]);

        Admin::factory()->create([
            'admin_name' => 'test',
            'email' => 'zohotestpr@gmail.com',
            'password' => 'Socxo@123',
            'role'=>'Super Admin'
        ]);
    }
}
