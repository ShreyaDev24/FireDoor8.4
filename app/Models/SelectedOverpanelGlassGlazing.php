<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedOverpanelGlassGlazing extends Model
{
    protected $table = 'selected_overpanel_glass_glazing';

    protected $fillable = [
        'glass_glazing_id',
        'editBy',
        'glassSelectedPrice',
        'glazingSelectedPrice',
        'status'
    ];
}
