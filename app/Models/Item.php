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

    /**
     * Scope for quotation items with optimized queries
     */
    public function scopeForQuotation($query, $quotationId, $versionId = 0) {
        if ($versionId > 0) {
            return $query->where('QuotationId', $quotationId)
                ->where('VersionId', $versionId);
        }
        return $query->where('QuotationId', $quotationId);
    }

    /**
     * Scope to get schedule items with joins
     */
    public function scopeWithScheduleDetails($query, $quotationId, $versionId = 0) {
        $selectColumns = [
            'items.FireRating',
            'items.SvgImage',
            'items.DoorType',
            'items.DoorQuantity',
            'items.DoorsetType',
            'items.SOWidth',
            'items.SOHeight',
            'items.SOWallThick',
            'items.AdjustPrice',
            'items.DoorsetPrice',
            'items.IronmongaryPrice',
            'items.itemId',
            'items.QuotationId',
            'items.VersionId',
            'item_master.id',
            'item_master.doorNumber',
            'item_master.floor'
        ];

        if ($versionId > 0) {
            return $query->join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
                ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
                ->where('quotation_version_items.version_id', $versionId)
                ->select($selectColumns);
        }

        return $query->join('item_master', 'items.itemId', 'item_master.itemID')
            ->where('items.QuotationId', $quotationId)
            ->select($selectColumns);
    }
}
