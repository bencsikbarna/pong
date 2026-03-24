<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin felhasználó
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sorpong.hu',
            'password' => Hash::make('admin1234'),
            'is_admin' => true,
        ]);

        // Minta csapatok
        $teams = [
            ['name' => 'Sörtámadók', 'email' => 'sortamadok@email.hu'],
            ['name' => 'Pong Királyok', 'email' => 'pongkiralyok@email.hu'],
            ['name' => 'Habos Bajnokok', 'email' => 'habosbajnokok@email.hu'],
            ['name' => 'Üveges Hősök', 'email' => 'uvegeshosok@email.hu'],
            ['name' => 'Sörös Legendák', 'email' => 'soroslegendak@email.hu'],
            ['name' => 'Pohár Mesterei', 'email' => 'poharmesterei@email.hu'],
        ];

        foreach ($teams as $teamData) {
            Team::create([
                'name' => $teamData['name'],
                'email' => $teamData['email'],
                'password' => Hash::make('csapat1234'),
                'contact_name' => 'Kapcsolattartó',
            ]);
        }

        // Minta esemény
        Event::create([
            'name' => 'Tavasz Kupa 2025',
            'description' => 'Az első tavaszi sörpong bajnokság! Legyél ott, és mutasd meg ki a legjobb!',
            'location' => 'Budapest, Söröző utca 1.',
            'event_date' => now()->addDays(14),
            'registration_deadline' => now()->addDays(7),
            'max_teams' => 16,
            'status' => 'registration_open',
            'created_by' => 1,
        ]);
    }
}
