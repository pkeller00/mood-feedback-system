<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Method to get feedback responses
     */
    public function get_data(Request $request, Meeting $meeting)
    {
        // Only provide access if user is owner of event
        if ($meeting->user_id != auth()->id()) {
            return response('You do not have access rights', 403);
        }

        $questions = $meeting->feedback_question()->get();
        $response_array = [];

        foreach ($questions as $question) {
            array_push($response_array, $question->feedback_response()->get());
        }

        return $response_array;
    }

    /**
     * Method to get content for the charts
     */
    public function get_chart(Request $request, Meeting $meeting)
    {
        // Only provide access if user is owner of event
        if ($meeting->user_id != auth()->id()) {
            return response('You do not have access rights', 403);
        }

        $questions = $meeting->feedback_question()->get();

        $response_array = [];

        foreach ($questions as $question) {
            array_push($response_array, $question->feedback_response()->get());
        }

        return $response_array;
    }
}
