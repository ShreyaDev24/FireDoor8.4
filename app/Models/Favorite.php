<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorite'; // 👈 specify table name

    protected $fillable = ['name', 'userId', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}

