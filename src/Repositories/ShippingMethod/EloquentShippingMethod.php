<?php

namespace Viviniko\Shipping\Repositories\ShippingMethod;

use Viviniko\Repository\EloquentRepository;
use Illuminate\Support\Facades\Config;

class EloquentShippingMethod extends EloquentRepository implements ShippingMethodRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('shipping.shipping_method'));
    }

    public function findByCountry($country)
    {
        return $this->createQuery()->whereIn('id', function ($query) use ($country) {
            return $query->select('method_id')
                ->from(Config::get('shipping.shipping_country_method_table'))
                ->where('country', $country);
        })->get();
    }
}