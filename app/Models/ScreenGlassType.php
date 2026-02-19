<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenGlassType extends Model
{
    protected $table = 'screen_glass_type';

    protected $fillable = [
        'FireRating',
        'GlassIntegrity',
        'GlassType',
        'DFRating',
        'HeightPoint1',
        'HeightPoint2',
        'WidthPoint1',
        'WidthPoint2',
        'TransomThickness',
        'TransomDepth',
        'AreaSize',
        'FrameDensity',
        'EditBy',
        'status',
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedScreenGlass::class, 'glass_id');
    }
}
