<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeafType extends Model
{
    protected $table = 'leaf_type';

    protected $fillable = [
        'Key',
        'LeafType',
        'UnderAttribute',
        'VicaimaDoorCore',
        'Seadec',
        'Deanta',
        'MMM',
        'EditBy'
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedLeafType::class, 'leaf_id');
    }
}
