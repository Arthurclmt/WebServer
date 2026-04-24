<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'CY216',
                'capacity' => 10,
                'description' => 'Petite salle de réunion'
            ],
            [
                'name' => 'CY208',
                'capacity' => 20,
                'description' => 'Salle de conférence'
            ],
            [
                'name' => 'Salle Serveur',
                'capacity' => 5,
                'description' => 'Salle sécurisée pour les serveurs'
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}