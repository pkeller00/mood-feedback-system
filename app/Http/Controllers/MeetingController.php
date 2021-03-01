<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use Illuminate\Support\Str;
use Inertia\Inertia;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Determine whether this is a request to make the event or feedback form
        if (!request(['formRequest'])) {
            $this->validateMeeting();
            $meeting = new Meeting(request(['name', 'meeting_start', 'meeting_end']));

            // Store the meeting information in the user's session
            session(compact('meeting'));

            // Render form for user to create a feedback form
            return Inertia::render('Meeting/CreateForm');
        } else {
            $this->validateFeedbackForm();
            // Event access code generator
            $isUnique = true;
            $code = "";
            do {
                $code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 7);
                $isUnique = Meeting::where('meeting_reference', $code)->doesntExist();
            } while ($isUnique == false);

            // Get meeting object from session
            $meeting = request()->session()->pull('meeting');
            $meeting->meeting_reference = $code;
            $meeting->user_id = auth()->id();
            $meeting->save();

            // Add each question to the meeting
            foreach (request('questions') as $item) {
                $question = new FeedbackQuestion();
                $question->question = $item['question'];
                $question->question_type = $item['question_type'];
                $question->meeting_id = $meeting->id;
                $question->save();
            }
            return redirect(route('meetings.show', compact('meeting')));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $meeting)
    {
        $questions = FeedbackQuestion::where('meeting_id', $meeting->id)->get(['id', 'question', 'question_type']);

        $meeting_date = Carbon::parse($meeting->meeting_start)->toDateTimeString();
        $current_date = Carbon::now()->toDateTimeString();

        return Inertia::render('Meeting/Show', [
            'meeting' => $meeting,
            'questions' => $questions,
            'no_edit' => ($meeting_date < $current_date),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meeting $meeting)
    {
        $meeting->update($this->validateMeeting());

        return redirect(route('meetings.show', $meeting));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        return redirect(route('meetings.index'));
    }

    protected function validateMeeting()
    {
        return request()->validate([
            'name' => 'required|max:255',
            'meeting_start' => 'required|date',
            'meeting_end' => 'required|date|after:meeting_start',
        ]);
    }

    protected function validateFeedbackForm()
    {
        return request()->validate([
            'questions' => 'required',
        ]);
    }
}
