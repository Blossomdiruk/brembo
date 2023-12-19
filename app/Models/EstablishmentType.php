<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentType extends Model
{
    use HasFactory;

    protected $table = 'establishment_types';
    public $timestamps = true;


    protected $fillable = [
        'establishment_name_en',
        'establishment_name_sin',
        'establishment_name_tam',
        'order',
        'status'
    ];
}
