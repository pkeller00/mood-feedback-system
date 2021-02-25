<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
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

        $meeting = Meeting::where('meeting_reference', request(['code']))->first();

        return redirect()->route('attendevents.create', compact('meeting'));
    }

    /**
     * Show the form for creating a feedback response.
     */
    public function create(Request $request, Meeting $meeting)
    {
        $questions = FeedbackQuestion::where('meeting_id', $meeting->id)->get();

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
        // needs the questions, and the responses
        $this->validateSubmittedFeedback();

        ddd($request);
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
        return request()->validate([
            'resp.*' => ['max:3'],
        ]);
    }
}