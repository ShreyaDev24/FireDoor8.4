<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
   use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ItemName','ItemPhoto','ItemStatus','ItemType'
    ];

      /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function ItemCategory(){
        return $this->hasMany(ItemCategory::class,'ItemId');
    }

    public function versionItem()
    {
        return $this->hasOne(QuotationVersionItems::class, 'itemID', 'itemId');
    }

    public function master()
    {
        return $this->belongsTo(ItemMaster::class, 'itemId', 'itemID');
    }

}
