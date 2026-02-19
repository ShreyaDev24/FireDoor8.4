<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedIntumescentSeals2  extends Model
{
    protected $table = 'selected_intumescentseals2';

    protected $fillable = [
        'intumescentseals2_id',
        'selected_intumescentseals2_user_id',   // 👈 REQUIRED
        'selected_configurableitems',
        'selected_doorname',
        'selected_firerating',
        'selected_tag',
        'selected_configuration',
        'selected_intumescentSeals',
        'selected_brand',
        'selected_firetested',
        'selected_Point1height',
        'selected_Point1width',
        'selected_Point2height',
        'selected_Point2width',
        'MeetingEdges',
        'selected_cost',
    ];

}
