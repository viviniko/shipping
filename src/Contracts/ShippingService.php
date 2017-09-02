<?php

namespace Viviniko\Shipping\Contracts;

interface ShippingService
{
    /**
     * Get shipping methods.
     *
     * @param $countryId
     * @param $weight
     * @return mixed
     */
    public function getShippingMethodsByCountryIdAndWeight($countryId, $weight);

    /**
     * Get shipping amount.
     *
     * @param $shippingMethodId
     * @param $countryId
     * @param $weight
     * @return float
     */
    public function getShippingAmount($shippingMethodId, $countryId, $weight);
}