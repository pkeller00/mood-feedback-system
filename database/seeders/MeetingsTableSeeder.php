<?php

namespace Database\Seeders;

use App\Models\Meeting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MeetingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Past
        Meeting::create([
            'meeting_reference' => 'XHDSF7H',
            'name' => 'Presentation: Fossil fuels',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->subDay()->subHours(4),
            'meeting_end' => Carbon::now()->subDay()->subHours(3),
        ]);
        Meeting::create([
            'meeting_reference' => 'U4GZWAX',
            'name' => 'New ideas for our blog',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->subDays(2)->subHours(2),
            'meeting_end' => Carbon::now()->subDays(1)->subHours(1),
        ]);
        Meeting::create([
            'meeting_reference' => 'QWUINFS',
            'name' => 'Presentation: Technology in Finance',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->subDays(2)->subHours(2),
            'meeting_end' => Carbon::now()->subDay()->subHours(1),
        ]);
        // Present
        Meeting::create([
            'meeting_reference' => 'OPASDK8',
            'name' => 'Annual Review',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->subHours(2),
            'meeting_end' => Carbon::now()->addDays(1),
        ]);
        Meeting::create([
            'meeting_reference' => 'SH78NHF',
            'name' => 'Golden Hour',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->subHours(2),
            'meeting_end' => Carbon::now()->addHours(1),
        ]);
        Meeting::create([
            'meeting_reference' => '7DNFSDA',
            'name' => 'Time travel: can we make it exist (Part 1)',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->subHours(1),
            'meeting_end' => Carbon::now()->addHours(1)->addMinutes(30),
        ]);
        // Future
        Meeting::create([
            'meeting_reference' => 'DJSADJQ',
            'name' => 'Annual General Meeting',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->addWeeks(3)->subDays(1),
            'meeting_end' => Carbon::now()->addWeeks(3)->addHours(3)->subDays(1),
        ]);
        Meeting::create([
            'meeting_reference' => 'KLXCPAA',
            'name' => 'Golden Hour',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->addWeek()->subHours(2)->subDays(3),
            'meeting_end' => Carbon::now()->addWeek()->addHours(1)->subDays(2),
        ]);
        Meeting::create([
            'meeting_reference' => 'FD8F9SO',
            'name' => 'Time travel: can we make it exist (Part 2)',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->addWeek()->subHours(1)->subDays(6),
            'meeting_end' => Carbon::now()->addWeek()->addHours(1)->addMinutes(30)->subDays(5),
        ]);
        Meeting::create([
            'meeting_reference' => 'JCXVCJE',
            'name' => 'Time travel: can we make it exist (Part 3 - The Final)',
            'user_id' => 1,
            'meeting_start' => Carbon::now()->addWeeks(2)->subHours(1)->subDays(3),
            'meeting_end' => Carbon::now()->addWeeks(2)->addHours(1)->addMinutes(30)->subDays(3),
        ]);
    }
}
