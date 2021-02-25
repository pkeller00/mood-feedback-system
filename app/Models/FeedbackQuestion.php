<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'question_type'];

    // protected $casts = [
    //     'response' => 'array'
    // ];
}
