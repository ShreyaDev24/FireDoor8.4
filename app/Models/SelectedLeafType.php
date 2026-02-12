<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedLeafType extends Model
{
    protected $table = 'selected_leaf_type';

    protected $fillable = [
        'leaf_id',
        'selectedPrice',
        'editBy'
    ];

    public function leaf()
    {
        return $this->belongsTo(LeafType::class, 'leaf_id');
    }
}
