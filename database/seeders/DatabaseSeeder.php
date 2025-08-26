<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
        ]);

        // También puedes crear un usuario admin si quieres:
        \App\Models\User::factory()->create([
            'name' => 'Admin BDYS',
            'email' => 'admin@bdys.com',
            'password' => bcrypt('12345678'),
            'rol_id' => 1, // admin
        ]);
    }
}
