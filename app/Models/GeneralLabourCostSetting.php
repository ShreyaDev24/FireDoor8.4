<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralLabourCostSetting extends Model
{
    protected $table = 'general_labour_cost_setting';
    protected $fillable = [
        'type',
        'labour_cost_per_man',
        'labour_cost_per_machine',
    ];
}
