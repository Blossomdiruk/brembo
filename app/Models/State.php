<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class State extends Model
{
     use HasFactory,Notifiable;
    
    protected $table = 'states';

    protected $fillable = [
        'name',
        'country_id',
        'country_code',
        'iso2'
    ];
}
