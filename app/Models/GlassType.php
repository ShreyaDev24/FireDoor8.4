<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlassType extends Model
{
    protected $table = 'glass_type';

    public function certificates()
    {
        return $this->hasMany(GlassCertificate::class);
    }
}
