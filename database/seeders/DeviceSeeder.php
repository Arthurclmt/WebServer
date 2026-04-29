<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appareil;

class DeviceSeeder extends Seeder{
    public function run(): void{
        $devices = [
        [
            'name'       => 'Switch1.1',
            'id'        => 1,
            'type' => 'Switch',
            'brand'     => 'Nintendo',
            'description' => 'Switch avec les DLCs MK8 et SmashBros.',
            'image'       => 'appareils/switch.jpeg' 
        ],
        [
            'name'       => 'Switch2',
            'id'        => 2,
            'type' => 'Switch2',
            'brand'     => 'Nintendo',
            'description' => 'Switch 2 avec des jeux exclusifs tels que Mario Kart World.',
            'image'       => 'appareils/switch2.jpeg' 
        ],
        [
            'name'       => 'Playstation',
            'id'        => 3,
            'type' => 'Playstation',
            'brand'     => 'Sony',
            'description' => 'Playstation avec les derniers jeux, FC26, Clairs Obscurs etc...',
            'image'       => 'appareils/Playstation.jpeg' 
        ],
        [
            'name'       => 'Ventilateur',
            'id'        => 4,
            'type' => 'Ventilateur',
            'brand'     => 'Epson',
            'description' => 'Ventillateur mis à disposition',
            'image'       => 'appareils/ventilateur.jpeg' 
        ],
        [
            'name'       => 'Frigo',
            'id'        => 5,
            'type' => 'Frigo',
            'brand'     => 'Sony',
            'description' => 'Frigo rempli pour votre plus grand plaisir',
            'image'       => 'appareils/frigo.jpeg' 
        ],
    ];

    foreach ($devices as $device) {
        Appareil::create($device);
    }
    }
}
