<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedIntumescentSealColor extends Model
{
    protected $table = 'selected_intumescent_seal_color';

    protected $fillable = [
        'intumescentSealColorId',
        'userId',
        'selectedPrice',
    ];

    public function sealColor()
    {
        return $this->belongsTo(IntumescentSealColor::class, 'intumescentSealColorId');
    }
}
