<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchitraveType extends Model
{
    protected $table = 'architrave_type';

    protected $fillable = [
        'Key',
        'ArchitraveType',
        'Streboard',
        'Halspan',
        'Flamebreak',
        'Stredor',
        'NormaDoorCore',
        'VicaimaDoorCore',
        'Seadec',
        'Deanta',
        'MMM',
        'Status',
        'editBy'
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedArchitraveType::class, 'architraveTypeId');
    }
}
