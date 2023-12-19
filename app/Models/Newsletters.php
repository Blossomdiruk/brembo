<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Newsletters extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'newsletters';

    protected $fillable = [
        'name',
        'status',
        'image',
        'description',
        'created_at',
        'updated_at'
    ];
}
