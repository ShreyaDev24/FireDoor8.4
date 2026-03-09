<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedLippingSpeciesItems extends Model {

    protected $fillable = [
        'selected_user_id',
        'selected_lipping_species_items_id',
        'selected_lipping_species_id',
        'selected_price',
        'selected_thickness',
        'selected_status'
    ];

    public function lipping_species_items()
    {
        return $this->belongsTo(LippingSpeciesItems::class, 'selected_lipping_species_items_id', 'id');
    }

}
