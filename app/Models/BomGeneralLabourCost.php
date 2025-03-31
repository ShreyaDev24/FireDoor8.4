<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomGeneralLabourCost extends Model
{
    protected $table = 'general_labour_cost';

    public function genLaborCost() {
        return $this->hasMany(GeneralLabourData::class, 'general_labour_cost_id', 'id');
    }
}