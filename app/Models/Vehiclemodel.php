<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vehiclemodel extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'vehicle_model';

    protected $fillable = [
        'name',
        'status',
        'brandID'
    ];
}
