<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverpanelGlassGlazing extends Model
{
    protected $table = 'overpanel_glass_glazing';

     protected $fillable = [
        'Key',
        'GlassType',
        'GlassIntegrity',
        'GlassThickness',
        'GlazingSystem',
        'GlazingThickness',
        'Beading',
        'BeadingHeight',
        'BeadingWidth',
        'FanLightWidth',
        'FanLightHeight',
        'SideScreenWidth',
        'SideScreenHeight',
        'TransomThickness',
        'TransomDepth',
        'Streboard',
        'Halspan',
        'Flamebreak',
        'Stredor',
        'NFR',
        'FD30',
        'FD60',
        'FixingDetails',
        'Status',
        'editBy',
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedOverpanelGlassGlazing::class, 'glass_glazing_id');
    }
}
