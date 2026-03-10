<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingIntumescentSeals2 extends Model
{
    protected $table = 'setting_intumescentseals2';

    protected $fillable = [
        'configurableitems',
        'doorname',
        'firerating',
        'tag',
        'configuration',
        'intumescentSeals',
        'brand',
        'firetested',
        'Point1height',
        'Point1width',
        'Point2height',
        'Point2width',
        'editBy',
        'FireOnly',
        'MeetingEdges',
        'customeleafTypes'
    ];

    public function selected_cost()
    {
        return $this->hasOne(SelectedIntumescentSeals2 ::class, 'intumescentseals2_id');
    }
}
