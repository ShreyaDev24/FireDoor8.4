<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedOptionLog extends Model
{
    protected $table = 'selected_option_logs';

    public $timestamps = false;

    protected $fillable = [
        'owner_id', 'option_type', 'option_key', 'option_label',
        'action', 'detected_by', 'created_at',
    ];
}
