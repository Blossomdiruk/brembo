<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Quiz extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'quizzes';
    protected $fillable = [
        'name',
        'description',
        'duration',
        'status',
        'valid_from',
        'valid_to'
    ];
}
