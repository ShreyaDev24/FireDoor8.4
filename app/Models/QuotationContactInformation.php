<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationContactInformation extends Model
{
    protected $table = 'quotation_contact_information';

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'QuotationId', 'id');
    }
}
