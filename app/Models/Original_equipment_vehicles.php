<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Original_equipment_vehicles extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'original_equipment_vehicles';

    protected $fillable = [
        'name',
        'status'
    ];
}
