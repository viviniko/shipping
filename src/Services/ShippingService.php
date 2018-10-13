<?php

namespace Viviniko\Shipping\Services;

interface ShippingService
{
    /**
     * Get shipping methods.
     *
     * @param $country
     * @param $weight
     * @return mixed
     */
    public function getShippingMethodsByCountryAndWeight($country, $weight);

    /**
     * Get shipping amount.
     *
     * @param $shippingMethodId
     * @param $country
     * @param $weight
     * @return float
     */
    public function getShippingAmount($shippingMethodId, $country, $weight);
}