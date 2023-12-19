<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ventilation_type extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'ventilation_type';

    protected $fillable = [
        'name',
        'status'
    ];
}
