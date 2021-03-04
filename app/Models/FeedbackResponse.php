<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{
    use HasFactory;

    public function feedback_question() {
        return $this->belongsTo(FeedbackQuestion::class);
    }
    public function response_information() {
        return $this->belongsTo(ResponseInformation::class);
    }
}
