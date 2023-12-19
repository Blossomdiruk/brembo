<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StructuredqAnswer extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'structuredq_answers';
    protected $fillable = [
        'exam_id',
        'structured_id',
        'workshop_id',
        'quest_answer'
    ];
}
