<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UsersTableSeeder::class);
        // \App\Models\Meeting::factory(10)->create();
        $this->call(MeetingsTableSeeder::class);
        $this->call(FeedbackQuestionsTableSeeder::class);
        $this->call(FeedbackResponsesTableSeeder::class);
    }
}
