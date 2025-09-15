<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSignature extends Model
{
    use HasFactory;
    protected $table = 'client_signature';
    protected $fillable = [
        'quotation_id',
        'version_id',
        'user_id',
        'signature_path',
        'signed_at',
    ];
}
