<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailHistory extends Model
{
    use HasFactory;

    protected $table = 'mail_histories';
    public $timestamps = true;


    protected $fillable = [
        'template_id',
        'sent_by',
        'sent_to',
        'subject',
        'status',
    ];

    public function mailtemplatedetails()
    {
        return $this->belongsTo('App\Models\MailTemplate', 'template_id', 'id');
    }

    public function userdetails()
    {
        return $this->belongsTo('App\Models\User', 'sent_by', 'id');
    }

    public function complaintdetails()
    {
        return $this->belongsTo('App\Models\RegisterComplaint', 'complaint_id', 'id');
    }
}
