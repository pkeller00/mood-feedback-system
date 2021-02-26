<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $meetings = DB::table('meetings')->where('user_id', auth()->id())->orderByDesc('meeting_start')->get();

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
        //general field only added once form has been made => if not there then need to make a form before storing    
        $hasForm = request('general');
        if($hasForm === null){
            $this->validateMeeting();
            $meeting = new Meeting(request(['name', 'meeting_start', 'meeting_end']));
            //need to let user create a form first
            return Inertia::render('Meeting/CreateForm', compact('meeting')); 
         }else{
            // Event access code generator
            $isUnique = true;
            $code = "";
            do {
                $isUnique = true;
                $code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 7);
                if (Meeting::where('meeting_reference', $code)->exists()) {
                    $isUnique = false;
                }
            } while ($isUnique == false);

            //Create a meeting object
            $meeting = new Meeting();
            $meeting->name = $hasForm['name'];
            $meeting->meeting_start = $hasForm['meeting_start'];
            $meeting->meeting_end = $hasForm['meeting_end'];
            $meeting->meeting_reference = $code;
            $meeting->user_id = auth()->id();
            $meeting->save();

            $saved_meeting = DB::table('meetings')->where('meeting_reference', $meeting->meeting_reference)->first();
            $questions = request('questions');
            
            foreach($questions as $item){
                $question = new FeedbackQuestion();
                $question->question = $item['question'];
                $question->question_type = $item['type'];
                $question->meeting_id = $saved_meeting->id;
                $question->save();
            }
            return redirect(route('meetings.index'));
            
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
        return Inertia::render('Meeting/Show', compact('meeting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        return Inertia::render('Meeting/Edit', compact('meeting'));
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
}
