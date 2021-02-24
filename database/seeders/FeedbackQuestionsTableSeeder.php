<?php

namespace Database\Seeders;

use App\Models\FeedbackQuestion;
use Illuminate\Database\Seeder;

class FeedbackQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FeedbackQuestion::create([
            'question' => 'question 1',
            'question_type' => 1,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => 'question 2',
            'question_type' => 3,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => 'question 3',
            'question_type' => 2,
            'meeting_id' => 11,
        ]);
    }
}
