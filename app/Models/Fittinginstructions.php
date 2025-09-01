<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FittingInstructions extends Model
{
    use HasFactory;

    protected $table = 'fitting_instructions'; // make sure table name is correct

    protected $fillable = [
        'user_id',
        'document_path',
        'status',
    ];
}
