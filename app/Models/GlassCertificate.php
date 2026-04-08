<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassCertificate extends Model
{
    use HasFactory;

    protected $table = 'glass_certificates';

    protected $fillable = [
        'user_id',
        'glass_type_id',
        'glass_thickness',
        'brand_of_core',
        'fire_rating',
        'certificate_reference',
        'expiry_date',
        'document_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function glassType()
    {
        return $this->belongsTo(GlassType::class);
    }
}

