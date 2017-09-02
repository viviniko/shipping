<?php

namespace Viviniko\Shipping\Models;

use Viviniko\Support\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $tableConfigKey = 'shipping.shipping_methods_table';

    protected $fillable = [
        'name', 'description', 'times', 'discount', 'is_active', 'sort'
    ];

    public function countryMethods()
    {
        return $this->hasMany(ShippingCountryMethod::class);
    }
}