<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

    protected $fillable = [
        'contest_id',
        'name',
        'description',
    ];

    public function contest()
    {
        return $this->belongsTo(\App\Models\Contest::class);
    }
}
