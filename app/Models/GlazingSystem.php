<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlazingSystem extends Model
{
    protected $table = 'glazing_system';

    protected $fillable = [
        'Key',
        'GlazingSystem',
        'GlazingThickness',
        'GlazingBeadFixingDetail',
        'VPAreaSize',
        'Status',
        'editBy',

        // Core fields
        'Streboard',
        'Halspan',
        'Flamebreak',
        'Stredor',
        'NormaDoorCore',
        'VicaimaDoorCore',
        'Seadec',
        'Deanta',
        'MMM',

        // Fire rating
        'NFR',
        'FD30',
        'FD60',
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedGlazingSystem::class, 'glazingId');
    }

    public function selectedGlazing()
    {
        return $this->hasOne(SelectedGlazingSystem::class, 'glazingId')
            ->where('userId', auth()->id());
    }

    public function glassGlazingSystems()
    {
        return $this->hasMany(GlassGlazingSystem::class, 'glazing_system');
    }
}
