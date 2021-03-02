<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'question_type'];

    public function meeting() {
        return $this->belongsTo(Meeting::class);
    }

    public function feedback_response() {
        return $this->hasMany(FeedbackResponse::class);
    }
}
