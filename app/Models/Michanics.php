<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Michanics extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'michanics';
    protected $fillable = [
        'name',
        'title',
        'email',
        'workshop_id',
        'status',
        'phone'
    ];
}
