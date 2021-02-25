<?php

namespace Database\Factories;

use App\Models\FeedbackQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackQuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FeedbackQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question' => $this->faker->sentence,
            'question_type' => 'slider',
            'meeting_id' => 1
        ];
    }
}
