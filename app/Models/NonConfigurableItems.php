<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class NonConfigurableItems extends Model
{
    protected $table = 'non_configurable_items';

    protected $fillable = [
        "name",
        "image",
        "product_code",
        "unit",
        "description",
        "price",
        "userId",
        "created_at",
        "updated_at"
    ];

    public function NonConfigurableItemStore()
    {
        return $this->hasMany(NonConfigurableItemStore::class,'nonConfigurableId');
    }
}
