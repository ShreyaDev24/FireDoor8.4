<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SideScreenItem extends Model
{
    use HasFactory;

    protected $table = 'side_screen_items'; // Specify table name if different from Laravel's default

    protected $fillable = [
        'QuotationId',
        'VersionId',
        'UserId',
        'itemID',
        'ScreenType',
        'Tolerance',
        'FireRating',
        'GlazingType',
        'SinglePane',
        'IGUInnerPane',
        'IGUOuterPane',
        'CAVITY',
        'GlassPane1Width',
        'GlassPane1Height',
        'GlassPane2Width',
        'GlassPane2Height',
        'GlassPane3Width',
        'GlassPane3Height',
        'GlassPane4Width',
        'GlassPane4Height',
        'GlassPane5Width',
        'GlassPane5Height',
        'GlassPane6Width',
        'GlassPane6Height',
        'GlassPane7Width',
        'GlassPane7Height',
        'GlassPane8Width',
        'GlassPane8Height',
        'GlassPane9Height',
        'GlassPane9Width',
        'GlassPane10Height',
        'GlassPane10Width',
        'GlassPane11Height',
        'GlassPane11Width',
        'GlassPane12Height',
        'GlassPane12Width',
        'GlassPane13Height',
        'GlassPane13Width',
        'GlassPane14Height',
        'GlassPane14Width',
        'GlassPane15Height',
        'GlassPane15Width',
        'GlassPane16Height',
        'GlassPane16Width',
        'Acoustic',
        'SpecialFeatuers',
        'Finish',
        'SOWidth',
        'SOHeight',
        'SODepth',
        'GlazingBeadShape',
        'GlazingBeadHeight',
        'GlazingBeadWidth',
        'GlazingBeadMaterial',
        'GlazingSystem',
        'GlazingSystemThickness',
        'GlazingSystemFixingDetail',
        'GlassLiner',
        'FrameThickness',
        'FrameDepth',
        'FrameWidth',
        'FrameHeight',
        'FrameMaterial',
        'SubFrameBottom',
        'SubFrameTop',
        'SubFrameLeft',
        'SubFrameRight',
        'SubFrameBottomThickness',
        'SubFrameBottomWidth',
        'SubFrameTopThickness',
        'SubFrameLeftThickness',
        'SubFrameRightThickness',
        'SubFrameMaterial',
        'TransomQuantity',
        'TransomType',
        'TransomThickness',
        'TransomDepth',
        'TransomMaterial',
        'TransomHeight1',
        'TransomWidth1',
        'MullionQuantity',
        'MullionType',
        'MullionThickness',
        'MullionMaterial',
        'MullionHeight1',
        'Transom1Thickness',
        'TransomHeightPoint1',
        'Transom2Thickness',
        'TransomHeightPoint2',
        'Transom3Thickness',
        'TransomHeightPoint3',
        'TransomHeightPoint4',
        'Mullion1Thickness',
        'MullionWidthPoint1',
        'Mullion2Thickness',
        'MullionWidthPoint2',
        'Mullion3Thickness',
        'MullionWidthPoint3',
        'MullionWidthPoint4'
    ];

    /**
     * Scope for side screen items with optimized queries
     */
    public function scopeForQuotation($query, $quotationId, $versionId = 0) {
        if ($versionId > 0) {
            return $query->where('QuotationId', $quotationId)
                ->where('VersionId', $versionId);
        }
        return $query->where('QuotationId', $quotationId);
    }

    /**
     * Scope to get side screen items with joins
     */
    public function scopeWithScreenDetails($query, $quotationId, $versionId = 0) {
        $selectColumns = [
            'side_screen_items.FireRating',
            'side_screen_items.VersionId',
            'side_screen_items.ScreenType',
            'side_screen_items.SOWidth',
            'side_screen_items.SOHeight',
            'side_screen_items.SODepth',
            'side_screen_items.GlazingType',
            'side_screen_items.ScreenPrice',
            'side_screen_items.id',
            'side_screen_item_master.screenNumber',
            'side_screen_item_master.floor',
            'side_screen_item_master.id as screenMasterid'
        ];

        return $query->join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')
            ->forQuotation($quotationId, $versionId)
            ->select($selectColumns);
    }
}
