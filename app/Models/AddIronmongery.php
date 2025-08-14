<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddIronmongery extends Model
{
    protected $table = 'add_ironmongery';

    public function folders()
    {
        return $this->belongsToMany(Folder::class, 'folder_ironmongery_sets');
    }
}
