<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ExamQuestions extends Model
{
    

    use HasFactory,Notifiable;
    protected $table = 'exam_questions';
    public $timestamps = true;
    protected $fillable = [
        'exam_id',
        'question_id',
        'quiz_type'
    ];
}
