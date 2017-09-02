<?php

namespace Viviniko\Shipping\Repositories\ShippingCountryMethod;

use Viviniko\Repository\SimpleRepository;

class EloquentShippingCountryMethod extends SimpleRepository implements ShippingCountryMethodRepository
{
    use ValidatesShippingCountryMethodData;
    protected $modelConfigKey = 'shipping.shipping_country_method';
    protected $fieldSearchable = ['shipping_method_id', 'shipping_country_id', 'group'];
}