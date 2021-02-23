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
}
