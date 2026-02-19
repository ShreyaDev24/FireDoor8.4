<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedGlazingSystem extends Model
{
    protected $table = 'selected_glazing_system';

    protected $fillable = [
        'glazingId',
        'userId',
        'selectedPrice',
    ];

    public function glazing()
    {
        return $this->belongsTo(GlazingSystem::class, 'glazingId');
    }
}
