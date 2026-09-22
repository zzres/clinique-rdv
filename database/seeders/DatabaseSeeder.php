<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();3

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        User::firstOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Patient Test',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            AdminUserSeeder::class,
            ConsultationTypeSeeder::class,
            AvailabilitySeeder::class,
        ]);
    }
}
