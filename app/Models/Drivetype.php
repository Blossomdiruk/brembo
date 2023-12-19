<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Drivetype extends Model
{
 use HasFactory,Notifiable;
    
    protected $table = 'drivetype';

    protected $fillable = [
        'name',
        'status'
    ];
}
