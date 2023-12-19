<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EnrollExams extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'enroll_exams';
    protected $fillable = [
        'exam_id',
        'workshop_id',
        'exam_status'
    ];
}
