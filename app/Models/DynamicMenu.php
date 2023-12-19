<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'title',
        'show_menu',
    ];
}
