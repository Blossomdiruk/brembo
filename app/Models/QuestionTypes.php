<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class QuestionTypes extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'question_types';
    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at'
    ];
}
