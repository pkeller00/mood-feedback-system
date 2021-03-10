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
        // Past
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 1,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 1,
        ]);
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 2,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 2,
        ]);
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 3,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 3,
        ]);

        // Present
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 4,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 4,
        ]);
        FeedbackQuestion::create([
            'question' => "Describe how you feel in one word?",
            'question_type' => 0,
            'meeting_id' => 4,
        ]);
        FeedbackQuestion::create([
            'question' => "How do you think this event is going?",
            'question_type' => 2,
            'meeting_id' => 4,
        ]);
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 5,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 5,
        ]);
        FeedbackQuestion::create([
            'question' => "Describe how you feel in one word?",
            'question_type' => 0,
            'meeting_id' => 5,
        ]);
        FeedbackQuestion::create([
            'question' => "How do you think this event is going?",
            'question_type' => 2,
            'meeting_id' => 5,
        ]);
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 6,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 6,
        ]);
        FeedbackQuestion::create([
            'question' => "Describe how you feel in one word?",
            'question_type' => 0,
            'meeting_id' => 6,
        ]);
        FeedbackQuestion::create([
            'question' => "How do you think this event is going?",
            'question_type' => 2,
            'meeting_id' => 6,
        ]);

        // Future
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 7,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 7,
        ]);
        FeedbackQuestion::create([
            'question' => "Describe how you feel in one word?",
            'question_type' => 0,
            'meeting_id' => 7,
        ]);
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 8,
        ]);
        FeedbackQuestion::create([
            'question' => "How do you think this event is going?",
            'question_type' => 2,
            'meeting_id' => 8,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 8,
        ]);
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 9,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 9,
        ]);
        FeedbackQuestion::create([
            'question' => "Did you enjoy today's meeting?",
            'question_type' => 3,
            'meeting_id' => 10,
        ]);
        FeedbackQuestion::create([
            'question' => "Do you have any comments, or concerns?",
            'question_type' => 1,
            'meeting_id' => 10,
        ]);
    }
}
