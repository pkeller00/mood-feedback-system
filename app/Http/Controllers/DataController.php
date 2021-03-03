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

        foreach ($questions as $key => $question) {
            // $question_type = $question->question_type;
            // return $question_type;

            $plots = [];
            $responses = $question->feedback_response()->get();


            // return $responses;
            foreach($responses as $response_x) {
                $chart_time = $response_x->created_at;
                $chart_score = $response_x->score;

                $plot = array(
                    'x' => $chart_time,
                    'y' => $chart_score,
                );

                array_push($plots, $plot);
            }

            $chartData['datasets'][0]['data'] = $plots;
            // return $chartData;




            $response_array[] = $chartData;
            // array_push($response_array, $chartData);
            // return $response_array;
        }



        return $response_array;
    }
}
