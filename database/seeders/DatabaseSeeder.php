<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
        ]);

        $supervisorEmail = 'admin@bdys.com';

        $user = User::firstOrCreate(
            ['email' => $supervisorEmail],
            [
                'name' => 'Supervisor BDYS',
                'password' => Hash::make('12345678'),
            ]
        );

        if (!$user->hasRole('Supervisor')) {
            $user->assignRole('Supervisor');
        }
    }
}
