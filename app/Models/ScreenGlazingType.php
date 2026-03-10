<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenGlazingType extends Model
{
    use HasFactory;
    protected $table = 'screen_glazing_type';

    protected $fillable = [
        'FireRating',
        'ScreenGlassId',
        'GlazingSystem',
        'GlazingThickness',
        'Beading',
        'BeadingHeight',
        'BeadingWidth',
        'FixingDetails',
        'status',
        'EditBy',
    ];

    public function glassType()
    {
        return $this->belongsTo(ScreenGlassType::class, 'ScreenGlassId');
    }

    public function selectedPrice()
    {
        return $this->hasOne(SelectedScreenGlazing::class, 'glazing_id');
    }

}
