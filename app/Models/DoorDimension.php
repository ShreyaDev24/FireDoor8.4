<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorDimension extends Model
{

    protected $table = 'door_dimension';

    protected $fillable = [
        'UserId',
        'configurableitems',
        'code',
        'inch_height',
        'inch_width',
        'mm_height',
        'mm_width',
        'fire_rating',
        'door_leaf_finish',
        'door_leaf_facing',
        'cost_price',
        'selling_price',
        'image',
        'is_deleted',
        'leaf_type',
        'editBy',
    ];

    public function selectedPrice()
    {
        return $this->hasOne(SelectedDoordimension::class, 'doordimension_id');
    }


}
