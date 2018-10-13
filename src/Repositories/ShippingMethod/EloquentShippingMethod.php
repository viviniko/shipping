<?php

namespace Viviniko\Shipping\Repositories\ShippingMethod;

use Viviniko\Repository\EloquentRepository;
use Illuminate\Support\Facades\Config;

class EloquentShippingMethod extends EloquentRepository implements ShippingMethodRepository
{
    public function __construct()
    {
        parent::__construct('shipping.shipping_method');
    }

    public function findByCountryId($countryId)
    {
        return $this->createQuery()->whereIn('id', function ($query) use ($countryId) {
            return $query->select('shipping_method_id')
                ->from(Config::get('shipping.shipping_country_method_table'))
                ->where('shipping_country_id', $countryId);
        })->get();
    }
}