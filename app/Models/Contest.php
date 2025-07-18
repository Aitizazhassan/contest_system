<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'access_level',
        'start_time',
        'end_time',
        'is_prize_awarded',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(\App\Models\Question::class);
    }

    public function prize()
    {
        return $this->hasOne(\App\Models\Prize::class);
    }
}
