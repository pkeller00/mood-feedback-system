<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;
use Inertia\Inertia;


class AttendEventController extends Controller
{
    public function attend(Request $request)
    {
        $this->validateAccessCode();

        $meeting = Meeting::where('meeting_reference', request(['code']))->first();

        $questions = FeedbackQuestion::where('meeting_id', $meeting->id)->get();
        // ddd($meeting, $questions);

        return Inertia::render('AttendEvent/Generate', [
            'meeting' => $meeting,
            'questions' => $questions,
        ]);
    }



    protected function validateAccessCode()
    {
        return request()->validate([
            'code' => ['required', 'exists:App\Models\Meeting,meeting_reference'],
        ], [
            'code.required' => 'An access code is required',
            'code.exists' => 'Invalid access code'
        ]);
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Meeting  $meeting
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($access_code)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\Meeting  $meeting
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Meeting $meeting)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Meeting  $meeting
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Meeting $meeting)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Meeting  $meeting
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Meeting $meeting)
    // {
    //     //
    // }
}
