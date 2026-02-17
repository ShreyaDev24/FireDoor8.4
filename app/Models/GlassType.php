<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassType extends Model
{
    protected $table = 'glass_type';

    protected $fillable = [
        'Key',
        'GlassType',
        'GlassThickness',
        'GlassIntegrity',
        'GlazingBeads',
        'VpAreaSize',
        'status',
        'EditBy',
        'Streboard',
        'Halspan',
        'Flamebreak',
        'Stredor',
        'NormaDoorCore',
        'VicaimaDoorCore',
        'Seadec',
        'Deanta',
        'MMM',
        'NFR',
        'FD30',
        'FD60',
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedGlassType::class, 'glass_id');
    }
}
