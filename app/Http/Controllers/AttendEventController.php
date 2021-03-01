<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackResponse;
use App\Models\ResponseInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;


class AttendEventController extends Controller
{

    /**
     * Show the form for creating a feedback response.
     */
    public function attend(Request $request)
    {
        $this->validateAccessCode();
        $current_user = auth()->id();
        $meeting = Meeting::where('meeting_reference', request(['code']))->first();

        if($current_user != null){
            $meeting_host = $meeting->user_id;
            if($meeting_host === $current_user){
                dd("Can't submit feedback for own event");
                return redirect()->route('home');
            }
        }

        return redirect()->route('attendevents.create', compact('meeting'));
    }

    /**
     * Show the form for creating a feedback response.
     */
    public function create(Request $request, Meeting $meeting)
    {
        // Gets the questions associated with a meeting
        $questions = FeedbackQuestion::where('meeting_id', $meeting->id)->get(['id', 'question', 'question_type']);

        return Inertia::render('AttendEvent/Create', [
            'meeting' => $meeting,
            'questions' => $questions,
        ]);
    }

    /**
     * Store a newly created feedback response in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate and then store the feedback response
        $this->validateSubmittedFeedback();

        $questions = request('questions');
        $responses = request('responses');

        $response_object = new ResponseInformation(request(['name', 'email']));
        $response_object->save();

        foreach ($responses as $key => $response) {
            $feedback_object = new FeedbackResponse;
            $feedback_object->response_id = $response_object->id;
            $feedback_object->question_id = $questions[$key]['id'];

            if ($questions[$key]['question_type'] === 0 || $questions[$key]['question_type'] === 1) {
                // Probably need to change the format of how it is stored in the JSON format dependent on how we call it in the front end
                $feedback_object->response = json_encode(['value' => $response]);

                // Sentiment score should be calculated and stored here - Google Cloud Natural Language API
                $feedback_object->score = 0;
            } elseif ($questions[$key]['question_type'] === 2) {
                // rating slider
                $feedback_object->response = json_encode(['rating' => $response]);

                $feedback_object->score = $response;
            } elseif ($questions[$key]['question_type'] === 3) {
                // emoji picker
                $feedback_object->response = json_encode(['emoji' => $response]);

                $feedback_object->score = $response;
            } elseif ($questions[$key]['question_type'] === 4) {
                // multiple choice question
                $feedback_object->response = json_encode(['value' => $response]);

                // Sentiment score should be calculated and stored here - Google Cloud Natural Language API
                $feedback_object->score = 0;
            }
            $feedback_object->save();
        }

        // Currently redirects to homepage
        // Probably should redirect to a success page that would allow the user to access the form again
        return redirect()->route('home');
    }

    /**
     * Validates access code to check if it has a corresponding `meeting_reference`
     */
    protected function validateAccessCode()
    {
        return request()->validate([
            'code' => ['required', 'exists:App\Models\Meeting,meeting_reference'],
        ], [
            'code.required' => 'An access code is required',
            'code.exists' => 'Invalid access code'
        ]);
    }

    /**
     * Validates access code to check if it has a corresponding `meeting_reference`
     */
    protected function validateSubmittedFeedback()
    {
        // TODO add appropriate validation for each of the responses once done
        return request()->validate([
            'responses.*' => ['required'],
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
        ]);
    }
}
