<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedScreenGlass extends Model
{
    protected $table = 'selected_screen_glass';

    protected $fillable = [
        'glass_id',
        'editBy',
        'glassSelectedPrice',
        'status',
    ];
}
