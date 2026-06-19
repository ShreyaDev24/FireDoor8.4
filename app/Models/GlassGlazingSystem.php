<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassGlazingSystem extends Model
{
    protected $table = 'glass_glazing_system';

    protected $fillable = [
        'ConfigurableItems',
        'NFR',
        'FD30',
        'FD60',
        'glass_id',
        'glazing_system',
        'GlassType',
        'GlazingSystem',
        'VPAreaSize',
        'VPWidth',
        'VPHeight',
        'UserId',
        'Status'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function glassType()
    {
        return $this->belongsTo(GlassType::class, 'glass_id');
    }

    public function glazingSystemRef()
    {
        return $this->belongsTo(GlazingSystem::class, 'glazing_system');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

    public function glazingSystem()
    {
        return $this->belongsTo(GlazingSystem::class, 'glazing_system');
    }
}
