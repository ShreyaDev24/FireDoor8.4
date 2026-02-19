<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedAccoustics extends Model
{
    protected $table = 'selected_accoustics';

    protected $fillable = [
        'accousticsId',
        'userId',
        'selectedPrice'
    ];

    public function accoustics()
    {
        return $this->belongsTo(Accoustics::class, 'accousticsId');
    }

}
