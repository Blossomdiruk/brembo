<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Workshops extends Model
{
     use HasFactory,Notifiable;
    
    protected $table = 'workshops';

    protected $fillable = [
        'business_name',
        'status',
        'email',
        'phone',
        'addressID',
        'status',
        'Contact_person',
        'ABN',
        'branchID',
        'created_at',
        'updated_at'
    ];
}
