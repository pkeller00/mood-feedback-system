<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{
    use HasFactory;

    /**
     * Get question that response belongs to
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feedback_question() {
        return $this->belongsTo(FeedbackQuestion::class);
    }

    /**
     * Get response information that has this feedback response
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function response_information() {
        return $this->belongsTo(ResponseInformation::class);
    }
}
