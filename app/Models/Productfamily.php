<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Productfamily extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'product_family';

    protected $fillable = [
        'name',
        'status'
    ];
    
}
