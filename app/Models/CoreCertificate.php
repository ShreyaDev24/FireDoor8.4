<?php

// app/Models/CoreCertificate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreCertificate extends Model
{
    protected $fillable = [
        'user_id',
        'brand_of_core',
        'fire_rating',
        'test_certificate_reference',
        'expiry_date',
        'document_path',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
