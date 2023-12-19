<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Bodytype extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'bodytype';

    protected $fillable = [
        'name',
        'status'
    ];
}
