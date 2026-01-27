<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationShipToInformation extends Model
{
    protected $table = 'quotation_ship_to_information';

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'QuotationId', 'id');
    }
}
