<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class QuizAttemptAnswers extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'quiz_attempt_answers';
    protected $fillable = [
        'quiz_attempt_id',
        'quiz_id',
        'workshop_id',
        'answer'
    ];
}
