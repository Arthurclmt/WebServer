<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
        [
        'title'    => 'EVENT1',
        'content'  => '',
        'image'    => 'events/affiche_LANmnesie.png',
        'type'     => 'event',
        'event_id' => '1',
        ],
        [
        'title'    => 'EVENT2',
        'content'  => '',
        'image'    => 'events/affiche_skyLANders.jpg',
        'type'     => 'event',
        'event_id' => '2',
        ],
        [
        'title'    => 'Braking News',
        'content'  => '',
        'image'    => 'images/Logo.png',
        ],
    ];
    foreach ($news as $new) {
        News::create($new);
    }
    }
}
