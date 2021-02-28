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
            'question' => 'What came first, the chicken or the egg? - short text input question',
            'question_type' => 0,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => 'Write an essay',
            'question_type' => 1,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => 'This should be a rating slider',
            'question_type' => 2,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => 'Are you happy? - emoji picker question',
            'question_type' => 3,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => 'Multiple choice question should be asked here',
            'question_type' => 4,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => 'This should be a text input question',
            'question_type' => 0,
            'meeting_id' => 11,
        ]);
        FeedbackQuestion::create([
            'question' => '5678 What came first, the chicken or the egg? - text input question',
            'question_type' => 0,
            'meeting_id' => 12,
        ]);
        FeedbackQuestion::create([
            'question' => '5678 Are you happy? - emoji picker question',
            'question_type' => 3,
            'meeting_id' => 12,
        ]);
        FeedbackQuestion::create([
            'question' => '5678 This should be a rating slider',
            'question_type' => 2,
            'meeting_id' => 12,
        ]);
        FeedbackQuestion::create([
            'question' => '5678 This should be a long text input question',
            'question_type' => 1,
            'meeting_id' => 12,
        ]);
    }
}
