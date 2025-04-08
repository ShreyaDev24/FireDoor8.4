<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BOMSetting extends Model
{
    protected $table = 'bom_setting';
    
    protected $fillable = [
        'type',
        'labour_cost_per_man',
        'labour_cost_per_machine',
    ];
}
