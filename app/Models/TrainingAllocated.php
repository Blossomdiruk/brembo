<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TrainingAllocated extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'training_allocated';

    protected $fillable = [
        'training_id',
        'workshop_id',
        'enrole_status'
    ];
}
