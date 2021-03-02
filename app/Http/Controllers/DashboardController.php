<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource for dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $current_time = Carbon::now()->toDateTime();

        $attended_events = DB::table('meetings')
            ->join('accessed_events', 'meetings.id', '=', 'accessed_events.meeting_id')
            ->where([['accessed_events.user_id', auth()->id()]])
            ->orderByDesc('accessed_events.last_accessed')
            ->take(3)->get();

        $meetings_past = Meeting::where([['user_id', auth()->id()], ['meeting_end', '<', $current_time]])->orderByDesc('meeting_end')->take(3)->get(['meeting_reference', 'name', 'meeting_start', 'meeting_end']);
        $meetings_current = Meeting::where([['user_id', auth()->id()], ['meeting_start', '<', $current_time], ['meeting_end', '>', $current_time]])->orderBy('meeting_start')->get(['meeting_reference', 'name', 'meeting_start', 'meeting_end']);
        $meetings_future = Meeting::where([['user_id', auth()->id()], ['meeting_start', '>', $current_time]])->orderBy('meeting_start')->take(3)->get(['meeting_reference', 'name', 'meeting_start', 'meeting_end']);

        return Inertia::render('Dashboard', compact('meetings_past', 'meetings_current', 'meetings_future', 'attended_events'));
    }
}
