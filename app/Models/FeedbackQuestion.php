<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'question_type'];

    /**
     * Get meeting that has feedback question
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meeting() {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Feedback question has many responses
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedback_response() {
        return $this->hasMany(FeedbackResponse::class);
    }
}
