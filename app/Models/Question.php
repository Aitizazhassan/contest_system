<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'contest_id',
        'text',
        'type',
    ];

    public function contest()
    {
        return $this->belongsTo(\App\Models\Contest::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Models\Answer::class);
    }
}
