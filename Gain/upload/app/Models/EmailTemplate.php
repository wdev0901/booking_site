<?php

namespace App\Models;

class EmailTemplate extends BaseModel
{
    protected $fillable = ['template_type','template_subject','default_content', 'custom_content'];

    public static function getEmailTemplate($request)
    {
        return EmailTemplate::orderBy($request->columnKey, $request->columnSortedBy)->get();
    }
}
