<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoorFrameConstruction extends Model
{
    use HasFactory;

    protected $table = 'door_frame_construction';
    
    protected $fillable = ['DoorFrameConstruction','Width','Height','UserId','hingeCenterCheck'];
}
