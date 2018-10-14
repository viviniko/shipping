<?php

namespace Viviniko\Shipping\Repositories\ShippingMethod;

interface ShippingMethodRepository
{
    public function all();

    public function create(array $data);

    public function update($id, $data);

    public function delete($id);

    public function find($id);

    public function findByCountry($country);
}