<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Competitor_brands extends Model
{
    use HasFactory,Notifiable;
    
    protected $table = 'competitor_brands';

    protected $fillable = [
        'name',
        'status'
    ];
}
