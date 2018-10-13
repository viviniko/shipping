<?php

namespace Viviniko\Shipping\Models;

use Viviniko\Country\Models\Country;
use Viviniko\Currency\Amount;
use Viviniko\Support\Database\Eloquent\Model;

class ShippingCountryMethod extends Model
{
    protected $tableConfigKey = 'shipping.shipping_country_method_table';

    protected $fillable = [
        'group', 'shipping_method_id', 'shipping_country_id', 'first_amount', 'first_weight', 'step_amount', 'step_weight', 'extra_amount'
    ];

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'shipping_country_id');
    }

    public function getFirstAmountAttribute($firstAmount)
    {
        return Amount::createBaseAmount($firstAmount);
    }

    public function getStepAmountAttribute($stepAmount)
    {
        return Amount::createBaseAmount($stepAmount);
    }

    public function getExtraAmountAttribute($extraAmount)
    {
        return Amount::createBaseAmount($extraAmount);
    }
}