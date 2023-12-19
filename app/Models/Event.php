<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    public $timestamps = true;


    protected $fillable = [
        'complaint_id',
        'officer_id',
        'event_icon',
        'event_title',
        'event_date',
        'start_time',
        'end_time',
        'event_people',
        'description',
        'event_color'
    ];
}
