<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Builders\ItemLoggingBuilder;

class Item extends Model
{
   use Notifiable;

    /**
     * Use the logging builder so every Item::where(...)->update([...]) records
     * a before / after entry in door_change_logs. Behaviour is otherwise the
     * standard Eloquent builder.
     */
    public function newEloquentBuilder($query): ItemLoggingBuilder
    {
        return new ItemLoggingBuilder($query);
    }

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
