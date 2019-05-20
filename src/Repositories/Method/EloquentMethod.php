<?php

namespace Viviniko\Shipping\Repositories\Method;

use Viviniko\Repository\EloquentRepository;
use Illuminate\Support\Facades\Config;

class EloquentMethod extends EloquentRepository implements MethodRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('shipping.method'));
    }

    public function findAllByCountry($country)
    {
        return $this->createQuery()->whereIn('id', function ($query) use ($country) {
            return $query->select('method_id')
                ->from(Config::get('shipping.shipping_country_method_table'))
                ->where('country', $country);
        })->get();
    }
}