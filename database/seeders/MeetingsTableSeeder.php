<?php

namespace Database\Seeders;

use App\Models\Meeting;
use Illuminate\Database\Seeder;

class MeetingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Meeting::create([
            'meeting_reference' => 'ABCD1234',
            'name' => 'Test Meeting',
            'user_id' => 11,
            'meeting_start' => '2021-02-24 15:52:00',
            'meeting_end' => '2021-02-24 16:52:00',
            // $this->faker->dateTimeInInterval($date='-6 days', $interval='+1 day'),
            // 'meeting_end' => $this->faker->dateTimeInInterval($date='-5 days', $interval='+1 day'),
        ]);
    }
}
