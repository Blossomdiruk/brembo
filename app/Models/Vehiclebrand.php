<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vehiclebrand extends Model
{
     use HasFactory,Notifiable;
    
    protected $table = 'vehicle_brand';

    protected $fillable = [
        'name',
        'status'
    ];
}
