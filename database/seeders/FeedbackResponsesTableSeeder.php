<?php

namespace Database\Seeders;

use App\Models\FeedbackResponse;
use App\Models\ResponseInformation;
use Illuminate\Database\Seeder;

class FeedbackResponsesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($meeting_id = 1; $meeting_id < 4; $meeting_id++) {
            $response_info = ResponseInformation::create([
                'meeting_id' => $meeting_id,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 1 + ($meeting_id - 1) * 2,
                'response' => json_encode(['score' => 1]),
                'score' => 1,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 2 + ($meeting_id - 1) * 2,
                'response' => json_encode(['value' => 'This is the future']),
                'score' => 0.9,
            ]);


            $response_info = ResponseInformation::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'meeting_id' => $meeting_id,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 1 + ($meeting_id - 1) * 2,
                'response' => json_encode(['score' => -1]),
                'score' => -1,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 2 + ($meeting_id - 1) * 2,
                'response' => json_encode(['value' => 'This is immoral']),
                'score' => -0.65,
            ]);


            $response_info = ResponseInformation::create([
                'name' => 'Amelia Pond',
                'email' => 'amelia@example.com',
                'meeting_id' => $meeting_id,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 1 + ($meeting_id - 1) * 2,
                'response' => json_encode(['score' => 0]),
                'score' => 0,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 2 + ($meeting_id - 1) * 2,
                'response' => json_encode(['value' => 'Step up the game']),
                'score' => 0,
            ]);


            $response_info = ResponseInformation::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'meeting_id' => $meeting_id,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 1 + ($meeting_id - 1) * 2,
                'response' => json_encode(['score' => -1]),
                'score' => -1,
            ]);
            FeedbackResponse::create([
                'response_information_id' => $response_info->id,
                'feedback_question_id' => 2 + ($meeting_id - 1) * 2,
                'response' => json_encode(['value' => 'This is really immoral']),
                'score' => -0.90,
            ]);
        }








        // ResponseInformation::create([
        //     'name' => 'Amelia Pond',
        //     'email' => 'amelia@example.com',
        //     'meeting_id' => 1,
        // ]);
        // ResponseInformation::create([
        //     'name' => 'Karen',
        //     'meeting_id' => 1,
        // ]);
    }
}
