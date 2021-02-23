<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = [
            'name' => 'Hello world',
            'meeting_reference' => 'XYZABCDE'
        ];

        $meetings = DB::table('meetings')->where('user_id', auth()->id())->get();

        return Inertia::render('Meeting/Index', ['meetings' => $meetings]);
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
        //
        // dd($request);
        $this->validateMeeting();

        $meeting = new Meeting(request(['name', 'meeting_date', 'start_time', 'end_time']));
        $meeting->meeting_reference = 'abcdef';
        $meeting->user_id = auth()->id();
        $meeting->save();

        return redirect(route('meetings.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        //
    }

    protected function validateMeeting()
    {
        return request()->validate([
            'name' => 'required|max:255',
            'meeting_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
    }
}
