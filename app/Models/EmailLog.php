<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_logs';
    public $timestamps = true;

    protected $fillable = [
        'name', 'email', 'subject', 'body','url','method', 'ip', 'user_id', 'status'
    ];
}
