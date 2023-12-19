<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StructuredQuestions extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'structured_questions';
    protected $fillable = [
        'name',
        'description',
        'duration',
        'status',
        'valid_from',
        'valid_to'
    ];
}
