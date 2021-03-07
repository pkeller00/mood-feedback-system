<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseInformation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];

    /**
     * Response information has many responses
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedback_response() {
        return $this->hasMany(FeedbackResponse::class);
    }

    public $table = 'response_informations';


}
