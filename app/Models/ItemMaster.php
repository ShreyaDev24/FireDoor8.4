<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMaster extends Model
{
    protected $table = 'item_master';

    public function item()
    {
        return $this->belongsTo(
            Items::class,
            'itemID',
            'itemId'
        );
    }
}
