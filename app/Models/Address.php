<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'address';

    protected $fillable = [
        'vAddressline1',
        'vAddressline2',
        'stateID',
        'cityID',
        'postcode',
        'created_at',
        'updated_at'
    ];
}
