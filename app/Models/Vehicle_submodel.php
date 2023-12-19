<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vehicle_submodel extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'vehicle_submodel';

    protected $fillable = [
        'name',
        'status',
        'brandID',
        'modelID'
    ];
}
