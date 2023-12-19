<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Trainning extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'trainnings';

    protected $fillable = [
        'vName',
        'tTrainning_description',
        'dStartDate',
        'dEndDate'
    ];
}
