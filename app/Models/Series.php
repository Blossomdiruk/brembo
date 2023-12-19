<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Series extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'series';

    protected $fillable = [
        'name',
        'status'
    ];
}
