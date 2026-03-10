<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedArchitraveType extends Model
{
    protected $table = 'selected_architrave_type';

    protected $fillable = [
        'architraveTypeId',
        'userId',
        'selectedPrice'
    ];

    public function architrave()
    {
        return $this->belongsTo(ArchitraveType::class, 'architraveTypeId');
    }
}
