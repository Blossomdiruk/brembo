<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $table = 'sms_templates';
    public $timestamps = true;


    protected $fillable = [
        'sms_template_name_en',
        'sms_template_name_sin',
        'sms_template_name_tam',
        'body_content_en',
        'body_content_sin',
        'body_content_tam',
        'status'
    ];
}
