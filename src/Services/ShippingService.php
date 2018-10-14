<?php

namespace Viviniko\Shipping\Services;

interface ShippingService
{
    public function getShippingMethod($id);

    public function shippingMethods();

    public function createShippingMethod(array $data);

    public function updateShippingMethod($id, array $data);

    public function deleteShippingMethod($id);

    public function getShippingCountryMethodsByShippingMethodId($id);

    public function createShippingCountryMethod(array $data);

    public function updateShippingCountryMethod($id, array $data);

    public function deleteShippingCountryMethod($id);
}