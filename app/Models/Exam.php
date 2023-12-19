<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Exam extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'exam';
    protected $fillable = [
        'name',
        'description',
        'duration',
        'status',
        'starting_date',
        'starting_time',
        'mcq_quez',
        'structured_quiz'
    ];
}
