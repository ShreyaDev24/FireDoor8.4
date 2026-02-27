<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedDoordimension extends Model
{
    protected $table = 'selected_doordimension';

    protected $fillable = [

        'doordimension_id',
        'doordimension_user_id',
        'selected_configurableitems',
        'selected_firerating',
        'selected_code',
        'selected_mm_height',
        'selected_mm_width',
        'selected_sellingprice',
        'selected_cost',
    ];

    public function dimension()
    {
        return $this->belongsTo(DoorDimension::class, 'doordimension_id');
    }
}
