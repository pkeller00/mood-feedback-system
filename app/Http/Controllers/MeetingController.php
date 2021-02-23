<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        // $data = [
        //     'name' => 'Hello world',
        //     'meeting_reference' => 'XYZABCDE'
        // ];

        $meetings = DB::table('meetings')->where('user_id', auth()->id())->orderByDesc('meeting_start')->get();
        // $meetings = DB::table('meetings')->where('user_id', auth()->id())->orderByDesc('meeting_date')->get();

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

        $meeting = new Meeting(request(['name', 'meeting_start', 'meeting_end']));
        // $meeting = new Meeting(request(['name', 'meeting_date', 'start_time', 'end_time']));
        $meeting->meeting_reference = Str::upper(Str::random(7));
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
        // dd($meeting);
        return Inertia::render('Meeting/Show', ['meeting' => $meeting->only('meeting_reference', 'name', 'meeting_start', 'meeting_end')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        // dd($meeting);

        return Inertia::render('Meeting/Edit', ['meeting' => $meeting->only('meeting_reference', 'name', 'meeting_start', 'meeting_end')]);
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
        return redirect($meeting->path());
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
            'meeting_start' => 'required|date',
            'meeting_end' => 'required|date|after:meeting_start',
        ]);
    }
}
