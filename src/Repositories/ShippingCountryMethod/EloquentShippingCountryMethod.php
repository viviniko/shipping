<?php

namespace Viviniko\Shipping\Repositories\ShippingCountryMethod;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\EloquentRepository;

class EloquentShippingCountryMethod extends EloquentRepository implements ShippingCountryMethodRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('shipping.shipping_country_method'));
    }
}