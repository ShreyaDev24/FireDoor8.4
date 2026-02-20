<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedScreenGlazing extends Model
{
    use HasFactory;
    protected $table = 'selected_screen_glazing';

     protected $fillable = [
        'glazing_id',
        'editBy',
        'glazingSelectedPrice',
    ];

    public function glazing()
    {
        return $this->belongsTo(ScreenGlazingType::class, 'glazing_id');
    }
}
