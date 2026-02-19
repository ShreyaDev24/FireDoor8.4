<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accoustics extends Model
{
    protected $table = 'accoustics';

    protected $fillable = [
        'Key',
        'Accoustics',
        'UnderAttribute',
        'file',
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
        return $this->hasOne(SelectedAccoustics::class, 'accousticsId');
    }

}
