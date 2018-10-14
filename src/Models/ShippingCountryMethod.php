<?php

namespace Viviniko\Shipping\Models;

use Viviniko\Support\Database\Eloquent\Model;

class ShippingCountryMethod extends Model
{
    protected $tableConfigKey = 'shipping.shipping_country_method_table';

    protected $fillable = [
        'group', 'method_id', 'country', 'first_amount', 'first_weight', 'step_amount', 'step_weight', 'extra_amount'
    ];

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'method_id');
    }
}