<?php

namespace Viviniko\Shipping\Repositories\ShippingCountryMethod;

use Viviniko\Repository\EloquentRepository;

class EloquentShippingCountryMethod extends EloquentRepository implements ShippingCountryMethodRepository
{
    public function __construct()
    {
        parent::__construct('shipping.shipping_country_method');
    }
}