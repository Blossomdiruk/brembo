<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProductPoints extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'points_redeem';
    protected $fillable = [
        'product_id',
        'workshop_id',
        'points',
        'product_code'
    ];
}
