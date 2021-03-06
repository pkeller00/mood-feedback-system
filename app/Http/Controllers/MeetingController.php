<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;
use RandomLib\Factory;


class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meetings = Meeting::where('user_id', auth()->id())->orderByDesc('meeting_start')->get();

        return Inertia::render('Meeting/Index', compact('meetings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Meeting/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store_event(Request $request)
    {
        // Determine whether this is a request to make the event or feedback form
        $this->validateMeeting();
        $meeting = new Meeting(request(['name', 'meeting_start', 'meeting_end']));

        // Store the meeting information in the user's session
        session(compact('meeting'));

        // Render form for user to create a feedback form
        return redirect(route('meetings.create_form'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function create_form(Request $request)
    {
        if ($request->session()->has('meeting')) {
            // ddd($request);
            $meeting = $request->session()->get('meeting');
            return Inertia::render('Meeting/CreateForm', compact('meeting'));
        } else {
            return redirect(route('meetings.create'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validateFeedbackForm();

        // Event access code generator
        $isUnique = true;
        $code = "";
        $factory = new Factory;
        $generator = $factory->getLowStrengthGenerator();
        do {
            $code = $generator->generateString(7, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            // $code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 7);
            $isUnique = Meeting::where('meeting_reference', $code)->doesntExist();
        } while ($isUnique == false);

        $meeting = request()->session()->pull('meeting');
        \DB::beginTransaction();
        try{
            $meeting->meeting_reference = $code;
            $meeting->user_id = auth()->id();
            $meeting->save();

            foreach (request('questions') as $item) {
                $question = new FeedbackQuestion();
                $question->question = $item['question'];
                $question->question_type = $item['question_type'];
                $question->meeting_id = $meeting->id;
                $question->save();
            }

        }
        catch(\Exception $e)
        {
            \DB::rollback();
            return redirect(route('meetings.index'));
        }

        \DB::commit();
        return redirect(route('meetings.show', compact('meeting')));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function show(Meeting $meeting)
    {
        // Only provide access if user is owner of event
        if ($meeting->user_id != auth()->id()) {
            return redirect(route('meetings.index'));
        }

        $questions = FeedbackQuestion::where('meeting_id', $meeting->id)->get(['id', 'question', 'question_type']);

        $meeting_date = Carbon::parse($meeting->meeting_start)->toDateTimeString();
        $current_date = Carbon::now()->toDateTimeString();

        return Inertia::render('Meeting/Show', [
            'meeting' => $meeting,
            'questions' => $questions,
            'event_started' => !($meeting_date > $current_date),
        ]);
    }

    /**
     * Display the feedback for a question.
     *
     * @param  \App\Models\Meeting  $meeting
     * @param string $question_number
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function show_feedback(Meeting $meeting, string $question_number)
    {
        /*Code to check if feedback is an integer*/
        if(is_numeric($question_number)){
            $question_number = $question_number + 0;
        }
        else{
            return redirect()->back();
        }
        if(!is_int($question_number)){
            return redirect()->back();
        }

        // Only provide access if user is owner of event
        if ($meeting->user_id != auth()->id()) {
            return redirect(route('meetings.index'));
        }

        $questions = FeedbackQuestion::where('meeting_id', $meeting->id)->get(['id', 'question', 'question_type']);

        if ($question_number > $questions->count() || $question_number <= 0) {
            return redirect()->back();
        }

        $question = $questions[$question_number - 1];

        if ($question->question_type !== 0 && $question->question_type !== 1) {
            return redirect()->back();
        }
        return Inertia::render('Meeting/ShowFeedback', compact('meeting', 'question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function edit(Meeting $meeting)
    {
        // Only provide access if user is owner of event
        if ($meeting->user_id != auth()->id()) {
            return redirect(route('meetings.index'));
        }

        $meeting_date = Carbon::parse($meeting->meeting_start)->toDateTimeString();
        $current_date = $date = Carbon::now()->toDateTimeString();

        if ($meeting_date > $current_date) {
            return Inertia::render('Meeting/Edit', compact('meeting'));
        } else {
            return redirect()->route('meetings.show', compact('meeting'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Meeting $meeting)
    {
        if ($meeting->user_id != auth()->id()) {
            return redirect(route('meetings.index'));
        }

        $meeting->update($this->validateMeeting());

        return redirect(route('meetings.show', $meeting));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Meeting $meeting)
    {
        if ($meeting->user_id != auth()->id()) {
            return redirect(route('meetings.index'));
        }

        $meeting->delete();

        return redirect(route('meetings.index'));
    }

    /**
     * Test if meeting is valid
     *
     * @return array<string, mixed>
     */
    protected function validateMeeting()
    {
        return request()->validate([
            'name' => 'required|max:255',
            'meeting_start' => 'required|date',
            'meeting_end' => 'required|date|after:meeting_start',
        ]);
    }

    /**
     * Test if feedback form is valid
     *
     * @return array<string, mixed>
     */
    protected function validateFeedbackForm()
    {
        return request()->validate([
            'questions' => ['required'],
        ], [
            'questions.required' => 'You have not provided any questions',
        ]);
    }
}
