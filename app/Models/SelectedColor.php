<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedColor extends Model
{
    protected $table = 'selected_color';

    protected $fillable = [
        'SelectedColorId',
        'DoorLeafFacingName',
        'SelectedUserId',
        'SelectedPrice'
    ];


    public function color()
    {
        return $this->belongsTo(Color::class,'SelectedColorId');
    }
}
