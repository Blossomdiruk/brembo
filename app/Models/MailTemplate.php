<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    use HasFactory;

    protected $table = 'mail_templates';
    public $timestamps = true;


    protected $fillable = [
        'mail_template_name',
        'body_content_en',
        'status',
        'mail_template_title',
        'other_email',
        'mail_template_name_sin',
        'mail_template_name_tam',
        'body_content_sin',
        'body_content_tam'
    ];

}
