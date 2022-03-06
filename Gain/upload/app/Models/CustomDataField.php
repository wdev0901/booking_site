<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomDataField extends Model
{
    protected $fillable = ['table_name','data_id','custom_fields_id','value'];
}
