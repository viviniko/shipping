<?php

namespace Viviniko\Shipping\Repositories\ShippingMethod;

use Viviniko\Repository\SimpleRepository;
use Illuminate\Support\Facades\Config;

class EloquentShippingMethod extends SimpleRepository implements ShippingMethodRepository
{
    use ValidatesShippingMethodData;

    protected $modelConfigKey = 'shipping.shipping_method';

    public function findByCountryId($countryId)
    {
        return $this->createModel()->whereIn('id', function ($query) use ($countryId) {
            return $query->select('shipping_method_id')
                ->from(Config::get('shipping.shipping_country_method_table'))
                ->where('shipping_country_id', $countryId);
        })->get();
    }
}