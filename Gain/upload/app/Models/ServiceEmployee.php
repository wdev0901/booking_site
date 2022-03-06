<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceEmployee extends Model
{
    protected $fillable = ['service_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
