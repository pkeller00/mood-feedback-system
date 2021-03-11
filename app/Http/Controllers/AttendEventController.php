<?php

namespace App\Http\Controllers;

use App\Models\AccessedEvent;
use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackResponse;
use App\Models\ResponseInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

# Imports the Google Cloud client library
use Google\Cloud\Language\LanguageClient;

class AttendEventController extends Controller
{

    /**
     * Show the form for creating a feedback response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attend(Request $request)
    {
        $this->validateAccessCode();
        $meeting = Meeting::where('meeting_reference', request(['code']))->first();

        // Validate whether meeting is still live
        $current_time = Carbon::now()->toDateTime();
        $meeting_end = Carbon::parse($meeting->meeting_end)->toDateTime();
        if ($meeting_end < $current_time) {
            return redirect()->back()->withErrors(['code' => 'Event has ended']);
        }

        return redirect()->route('attendevents.create', compact('meeting'));
    }

    /**
     * Show the form for creating a feedback response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request, Meeting $meeting)
    {
        // If meeting has ended we should restrict access to making new feedback responses
        $current_time = Carbon::now()->toDateTime();
        $meeting_end = Carbon::parse($meeting->meeting_end)->toDateTime();
        if ($meeting_end < $current_time) {
            return redirect()->back();
        }

        if (auth()->id()) {
            AccessedEvent::updateOrCreate([
                'user_id' => auth()->id(),
                'meeting_id' => $meeting->id
            ], [
                'last_accessed' => $current_time
            ]);
        }

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Meeting $meeting)
    {
        // validate and then store the feedback response
        $this->validateSubmittedFeedback();

        // If meeting has ended we should restrict access to making new feedback responses
        $current_time = Carbon::now()->toDateTime();
        $meeting_start = Carbon::parse($meeting->meeting_start)->toDateTime();
        $meeting_end = Carbon::parse($meeting->meeting_end)->toDateTime();
        if ($current_time < $meeting_start) {
            return redirect()->back()->withErrors(['event_started' => "Event has not begun"]);
        }
        if ($meeting_end < $current_time) {
            return redirect()->route('home');
        }


        $questions = request('questions');
        $responses = request('responses');

        if (count($responses) !== count($questions)) {
            return redirect()->back()->withErrors(['not_filled' => "You have not filled in all fields"]);
        }

        $response_object = new ResponseInformation(request(['name', 'email']));
        
        \DB::beginTransaction();
        try{
            $response_object->meeting_id = $meeting->id;

            $response_object->save();

            $projectId = 'mood-feedback-sy-1614713095205';

            //dd(base_path());
            $path="/var/www/html/Mood-Feedback-System-729677aa2e6b.json";

            # Instantiates a client
            $language = new LanguageClient([
                'projectId' => $projectId,
                'keyFile' => json_decode(file_get_contents($path), true),
            ]);

            foreach ($responses as $key => $response) {
                $feedback_object = new FeedbackResponse;
                $feedback_object->response_information_id = $response_object->id;
                $feedback_object->feedback_question_id = $questions[$key]['id'];

                if(ctype_digit($questions[$key]['question_type'])){
                    $questions[$key]['question_type'] = (int)($questions[$key]['question_type']);
                    if($questions[$key]['question_type'] < 0 || $questions[$key]['question_type'] > 4){
                        throw new \Exception('Type not in range');
                    }
                }
                else if(!(is_numeric($questions[$key]['question_type']))){
                    throw new \Exception('Type not string');
                }

                if ($questions[$key]['question_type'] === 0 || $questions[$key]['question_type'] === 1) {
                    // Probably need to change the format of how it is stored in the JSON format dependent on how we call it in the front end
                    $feedback_object->response = json_encode(['value' => $response]);

                    $annotation = $language->analyzeSentiment($response);
                    $sentiment = $annotation->sentiment();//This gives us both magnitude and score so not sure which one we want

                    // Sentiment score should be calculated and stored here - Google Cloud Natural Language API
                    $feedback_object->score = $sentiment['score'];

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
        }
        catch(\Exception $e)
        {
            \DB::rollback();
            return redirect(route('home'));
        }
        \DB::commit();

        // Currently redirects to homepage
        if (auth()->id()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('home');
    }

    /**
     * Validates access code to check if it has a corresponding `meeting_reference`
     *
     * @return array<string, mixed>
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
     *
     * @return array<string, mixed>
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