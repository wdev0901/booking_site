<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    protected $fillable = [
        'title',
        'start_time',
        'end_time',
        'is_enable'
    ];
}
