<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Warrent_incident extends Model
{
    use HasFactory,Notifiable;
    protected $table = 'warenty_incidents';

    protected $fillable = [
        'product_id',
        'product_name',
        'vMake',
        'vModel',
        'vYOM',
        'vVINNo',
        'vKMFitted',
        'vODOmeter',
        'cCityuse',
        'cHightWayUse',
        'cOffRoadUse',
        'cTowingUse',
        'cMountainUse',
        'cOtherUse',
        'vOtherUseReason',
        'cNewDrums',
        'cDiskMachined',
        'cNewPads',
        'cSlideGreased',
        'vWheelTorque',
        'vAntiSequel',
        'vDiskClean',
        'description',
        'comment',
        'warrenty_status',
        'workshop_id',
        'purchased_date'
    ];
}
