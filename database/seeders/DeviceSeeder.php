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
            'description' => 'Switch avec les DLCs MK8 et SmashBros.'
        ],
        [
            'name'       => 'Switch2',
            'id'        => 2,
            'type' => 'Switch2',
            'brand'     => 'Nintendo',
            'description' => 'Switch 2 avec des jeux exclusifs tels que Mario Kart World.'
        ],
    ];

    foreach ($devices as $device) {
        Appareil::create($device);
    }
    }
}
