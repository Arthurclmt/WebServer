<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AllowedMember;

class AllowedMemberSeeder extends Seeder{
    public function run(): void{
        DB::table('allowed_members')->truncate();

        $members = [
            ['email' => 'user1@example.com', 'is_registered' => false, 'added_by' => null],
            ['email' => 'user2@example.com',   'is_registered' => false, 'added_by' => null],
        ];

        foreach ($members as $member) {
            AllowedMember::create($member);
        }
    }
}
