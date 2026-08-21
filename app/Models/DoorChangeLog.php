<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorChangeLog extends Model
{
    protected $table = 'door_change_logs';

    public $timestamps = false;

    protected $fillable = [
        'item_id', 'quotation_id', 'version_id', 'door_type', 'action',
        'field', 'label', 'old_value', 'new_value', 'changed_by', 'created_at',
    ];

    /**
     * Columns that change on their own or are too large to be worth recording.
     */
    public const IGNORED_FIELDS = [
        'created_at', 'updated_at', 'SvgImage',
        'leaf_price_delta', 'leaf_price_delta_adjust',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
