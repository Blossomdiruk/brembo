<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $table = 'sms_logs';
    public $timestamps = true;

    protected $fillable = [
        'subject', 'name', 'mobile', 'url','method', 'ip', 'user_id', 'status'
    ];
}
