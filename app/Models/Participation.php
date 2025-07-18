<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contest_id',
        'score',
        'finished_at',
    ];

    protected $casts = [
        'finished_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function contest()
    {
        return $this->belongsTo(\App\Models\Contest::class);
    }

    public function responses()
    {
        return $this->hasMany(\App\Models\Response::class);
    }
}
