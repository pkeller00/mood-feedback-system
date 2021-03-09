<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Method to get feedback responses
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meeting  $meeting
     * @param  \App\Models\FeedbackQuestion  $question
     * @return array<int, array<string,mixed>>|\Illuminate\Http\Response
     */
    public function get_data(Request $request, Meeting $meeting, FeedbackQuestion $question)
    {
        // Only provide access if user is owner of event
        if ($meeting->user_id != auth()->id()) {
            return response('You do not have access rights', 403);
        }
        $responses = $question->feedback_response()->get();

        $new_responses = [];

        foreach ($responses as $response_x) {
            $response_info = $response_x->response_information()->get(['name', 'email']);
            $new_responses[] = array('response' => $response_x, 'response_info' => $response_info);
        }
        return $new_responses;
    }

    /**
     * Method to get content for the charts
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meeting  $meeting
     * @return array<int, array<string,mixed>>|\Illuminate\Http\Response
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
            $chartData = null;
            $question_type = $question->question_type;
            $responses = $question->feedback_response()->get();

            if ($question_type === 0 || $question_type === 1) {
                // text input
                $plots = [];
                $avg_plots = [];
                $total_score = 0;

                foreach ($responses as $id => $response_x) {
                    $chart_time = $response_x->created_at;
                    $chart_score = $response_x->score;
                    $total_score += $chart_score;
                    $plot = array(
                        'x' => $chart_time,
                        'y' => $chart_score,
                    );
                    $avg_plot = array(
                        'x' => $chart_time,
                        'y' => $total_score / ($id + 1),
                    );

                    $plots[] = $plot;
                    $avg_plots[] = $avg_plot;
                }
                $chartData['datasets'][0]['data'] = $plots;
                $chartData['datasets'][0]['showLine'] = false;
                $chartData['datasets'][0]['label'] = "Mood";
                $chartData['datasets'][0]['backgroundColor'] = 'rgba(0,0,0,1)';
                $chartData['datasets'][0]['borderColor'] = 'rgba(0, 0, 0, 1)';
                $chartData['datasets'][1]['data'] = $avg_plots;
                $chartData['datasets'][1]['label'] = "Average Mood";
                $chartData['datasets'][1]['backgroundColor'] = 'rgba(0,0,0,0)';
                $chartData['datasets'][1]['borderColor'] = '#F59E0B';
            } elseif ($question_type === 2) {
                // rating slider

                $plots = [];
                $avg_plots = [];
                $total_score = 0;

                foreach ($responses as $id => $response_x) {
                    $chart_time = $response_x->created_at;
                    $chart_score = $response_x->score;
                    $total_score += $chart_score;
                    $plot = array(
                        'x' => $chart_time,
                        'y' => $chart_score,
                    );
                    $avg_plot = array(
                        'x' => $chart_time,
                        'y' => $total_score / ($id + 1),
                    );

                    $plots[] = $plot;
                    $avg_plots[] = $avg_plot;
                }
                $chartData['datasets'][0]['data'] = $plots;
                $chartData['datasets'][0]['showLine'] = false;
                $chartData['datasets'][0]['label'] = "Ratings";
                $chartData['datasets'][0]['backgroundColor'] = 'rgba(0,0,0,1)';
                $chartData['datasets'][0]['borderColor'] = 'rgba(0, 0, 0, 1)';
                $chartData['datasets'][1]['data'] = $avg_plots;
                $chartData['datasets'][1]['label'] = "Average Rating";
                $chartData['datasets'][1]['backgroundColor'] = 'rgba(0,0,0,0)';
                $chartData['datasets'][1]['borderColor'] = '#F59E0B';
            } elseif ($question_type === 3) {
                // emoji picker
                $counts = [0, 0, 0];

                foreach ($responses as $response_x) {
                    $chart_score = $response_x->score;

                    if ($chart_score == -1) {
                        $counts[0] += 1;
                    } elseif ($chart_score == 0) {
                        $counts[1] += 1;
                    } else {
                        $counts[2] += 1;
                    }
                }

                $chartData['datasets'][0]['data'] = $counts;
                $chartData['datasets'][0]['backgroundColor'] = [
                    '#EF4444',
                    '#F59E0B',
                    '#10B981'
                ];
                $chartData['labels'] = ["Sad", "Neutral", "Happy"];
                $chartData['datasets'][0]['borderColor'] = '#FFF';
            } else {
                $plots = [];

                foreach ($responses as $response_x) {
                    $chart_time = $response_x->created_at;
                    $chart_score = $response_x->score;

                    $plot = array(
                        'x' => $chart_time,
                        'y' => $chart_score,
                    );

                    $plots[] = $plot;
                }
                $chartData['datasets'][0]['data'] = $plots;
                $chartData['datasets'][0]['label'] = "Rating";
                $chartData['datasets'][0]['backgroundColor'] = 'rgba(0,0,0,0)';
                $chartData['datasets'][0]['borderColor'] = 'rgba(255, 0, 0, 1)';
            }

            // echo $chartData;
            $response_array[] = $chartData;
        }
        return $response_array;
    }
}
