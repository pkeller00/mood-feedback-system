<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'meeting_start', 'meeting_end'];

    public function path()
    {
        return route('meetings.show', $this);
    }

    public function getMeetingStartAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }
    public function getMeetingEndAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

    public function getRouteKeyName()
    {
        return 'meeting_reference';
    }

    public function feedback_question() {
        return $this->hasMany(FeedbackQuestion::class);
    }

    public function response_information() {
        return $this->hasManyThrough(ResponseInformation::class, FeedbackQuestion::class);
    }
}
