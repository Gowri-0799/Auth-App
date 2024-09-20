<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Admin::factory()->create([
            'admin_name' => 'admin',
            'email' => 'admin@email.com',
            'password' => 'admin123'
        ]);

        Admin::factory()->create([
            'admin_name' => 'user',
            'email' => 'user@email.com',
            'password' => 'user123'
        ]);

        Admin::factory()->create([
            'admin_name' => 'test',
            'email' => 'test@email.com',
            'password' => 'test123'
        ]);
    }
}
