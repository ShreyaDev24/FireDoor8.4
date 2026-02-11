<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorLeafFacing extends Model
{
    protected $table = 'door_leaf_facing';

    protected $fillable = [
        'Key',
        'doorLeafFacing',
        'doorLeafFacingValue',
        'Streboard',
        'Halspan',
        'NormaDoorCore',
        'VicaimaDoorCore',
        'Seadec',
        'Deanta',
        'Flamebreak',
        'Stredor',
        'MMM',
        'editBy'
    ];

    public function selectedPrice()
    {
        return $this->hasOne(
            SelectedDoorLeafFacing::class,
            'doorLeafFacingId'
        );
    }

}


