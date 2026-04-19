<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder{
    public function run(): void{
        $events = [
        [
            'title'       => 'LANFRANK',
            'slug'        => 'LANFRANK',
            'description' => 'Une lan douteuse mon reuf',
            'content'     => '',
            'event_date'  => '2026-06-15',
        ],
        [
            'title'       => 'CitySkyLAN',
            'slug'        => 'Kaboum',
            'description' => 'Une belle date pour un beau event',
            'content'     => '',
            'event_date'  => '2026-09-11',
        ],
    ];

    foreach ($events as $event) {
        Event::create($event);
    }
    }
}
