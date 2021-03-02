<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function get_data(Request $request, Meeting $meeting)
    {
        // Only provide access if user is owner of event
        if ($meeting->user_id != auth()->id()) {
            return redirect(route('meetings.index'));
        }

        $questions = $meeting->feedback_question();

        return $questions->get();
        $response_array = [];

        foreach ($questions as $key => $question) {
            # code...
            array_push($response_array, $question->feedback_response()->all());
        }

        // ddd($response_array);

        return $response_array;
    }
}
