<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
     protected $table = 'folders';
     protected $fillable = ['name','user_id'];

      public function ironmongerySets()
    {
        return $this->belongsToMany(AddIronmongery::class, 'folder_ironmongery_sets');
    }
}
