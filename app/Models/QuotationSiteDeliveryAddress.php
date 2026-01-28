<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationSiteDeliveryAddress extends Model
{
    protected $table = 'quotation_sitedeliveryaddress';

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'QuotationId', 'id');
    }
}
