<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLabourData extends Model
{
    use HasFactory;
    protected $table = 'general_labour_cost_setting_data';

    public function general_labour_cost() {
        return $this->belongsToMany(BomGeneralLabourCost::class, 'id', 'genLaborCost');
    }

    public function laborBomSettings() {
        return $this->hasOne(GeneralLabourCostSetting::class, 'id', 'bom_setting_id');
    }
}
