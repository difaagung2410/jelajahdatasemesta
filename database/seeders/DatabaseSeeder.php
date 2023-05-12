<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(2)->create();

        // Menambahkan data user sebagai admin
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'is_admin' => 1
        ]);

        // Menambahkan user sebagai non admin
        \App\Models\User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
        ]);
    }
}
