<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'meeting_reference' => Str::upper(Str::random(7)),
            'name' => $this->faker->sentence,
            'user_id' => User::factory(),
            'meeting_start' => $this->faker->dateTimeInInterval($date='-1 week', $interval='+1 day'),
            'meeting_end' => $this->faker->dateTimeInInterval($date='-6 days', $interval='+1 day'),
            // 'meeting_end' => $this->faker->date(),
            // 'meeting_date' => $this->faker->date(),
            // 'start_time' => $this->faker->time('11:00:00'),
            // 'end_time' => $this->faker->time('12:00:00'),
        ];
    }
}
