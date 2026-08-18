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
            User::updateOrCreate(
            ['email' => 'vincentluhulima6010@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admininnochapter2'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['email' => 'rizki@gmail.com'],
            [
                'name' => 'INI IKI',
                'password' => bcrypt('admininnochapter2'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['email' => '60202302171@std.uin-suka.ac.id'],
            [
                'name' => 'Kelompok 1',
                'password' => bcrypt('kelompok1'),
                'role' => 'participant',
            ]
        );
        // Admin Account for Filament
        User::updateOrCreate(
            ['email' => 'admin@innoventure.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admininnochapter2'),
                'role' => 'admin',
            ]
        );

        // Dummy Participant
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'participant',
            ]
        );

        $this->call([
            MobileLegendsDummySeeder::class,
            PesertaSeeder::class,
            // WebDevAspekPenilaianSeeder::class,
            // UiUxAspekPenilaianSeeder::class,
        ]);
    }
}
