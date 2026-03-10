<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntumescentSealColor extends Model
{
    protected $table = 'intumescent_seal_color';

    protected $fillable = [
        'Key',
        'IntumescentSealColor',
        'Status',
        'editBy',
        'Streboard',
        'Halspan',
        'Flamebreak',
        'Stredor',
        'NormaDoorCore',
        'VicaimaDoorCore',
        'Seadec',
        'Deanta',
        'MMM',
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedIntumescentSealColor::class, 'intumescentSealColorId');
    }
}
