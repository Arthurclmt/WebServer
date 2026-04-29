<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder{
    public function run(): void{
        $events = [
        [
            'title'       => 'EVENT1',
            'slug'        => 'event1',
            'description' => 'super event',
            'content'     => '',
            'event_date'  => '2026-06-15',
            'image'       => 'events/affiche_LANmnesie.png',
            'id'          =>'1'
        ],
        [
            'title'       => 'EVENT2',
            'slug'        => 'event2',
            'description' => 'Une belle date pour un beau event',
            'content'     => '',
            'event_date'  => '2026-09-12',
            'image'       => 'events/affiche_skyLANders.jpg',
            'id'          =>'2'
        ],
    ];

    foreach ($events as $event) {
        Event::create($event);
    }
    }
}
