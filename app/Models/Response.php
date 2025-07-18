<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'participation_id',
        'question_id',
        'answer_id',
        'is_correct',
    ];

    public function participation()
    {
        return $this->belongsTo(\App\Models\Participation::class);
    }

    public function question()
    {
        return $this->belongsTo(\App\Models\Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(\App\Models\Answer::class);
    }
}
