<?php

namespace Viviniko\Shipping\Repositories\ShippingMethod;

interface ShippingMethodRepository
{
    public function find($id);

    public function findByCountryId($countryId);
}