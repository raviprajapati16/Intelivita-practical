<?php

namespace Database\Seeders;

use App\Models\Leaderboard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Leaderboard::factory()->count(20)->create();
    }
}
