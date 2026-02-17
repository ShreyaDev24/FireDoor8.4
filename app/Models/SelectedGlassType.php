<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedGlassType extends Model
{
    protected $table = 'selected_glass_type';

    protected $fillable = [
        'glass_id',
        'editBy',
        'selectedPrice'
    ];

    public function glass()
    {
        return $this->belongsTo(GlassType::class, 'glass_id');
    }
}
