<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vehicletype extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'vehicle_type';

    protected $fillable = [
        'name',
        'status'
    ];
}
