<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'meeting_start', 'meeting_end'];

    /**
     * Route to show meetings
     * 
     * @return string
     */
    public function path()
    {
        return route('meetings.show', $this);
    }

    /**
     * Get meeting start
     * 
     * @param App\Models\DateTime $value
     * @return string
     */
    public function getMeetingStartAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

    /**
     * Get meeting end
     * 
     * @param App\Models\DateTime $value
     * @return string
     */
    public function getMeetingEndAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

    /**
     * Route will use meeting reference
     * 
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'meeting_reference';
    }

    /**
     * Meeting has many feedback questions
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedback_question() {
        return $this->hasMany(FeedbackQuestion::class);
    }

    /**
     * Meeting has many response information objects
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function response_information() {
        return $this->hasMany(ResponseInformation::class);
    }
}
