<?php

namespace App\Models;


class SmsTemplate extends BaseModel
{
    protected $fillable = ['template_type', 'template_subject', 'default_content', 'custom_content'];
}
