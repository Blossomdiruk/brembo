<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleSmsEmailRecord extends Model
{
    use HasFactory;

    protected $table = 'multiple_sms_email_record';
    public $timestamps = true;


    protected $fillable = [
        'name',
        'mobile',
        'email'
    ];

}
