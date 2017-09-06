<?php

namespace Viviniko\Shipping\Services;

use Viviniko\Shipping\Contracts\ShippingService as ShippingServiceInterface;
use Viviniko\Shipping\Repositories\ShippingMethod\ShippingMethodRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ShippingServiceImpl implements ShippingServiceInterface
{
    protected $shippingMethods;

    public function __construct(ShippingMethodRepository $shippingMethods)
    {
        $this->shippingMethods = $shippingMethods;
    }

    /**
     * Get shipping methods.
     *
     * @param $countryId
     * @param $weight
     * @return mixed
     */
    public function getShippingMethodsByCountryIdAndWeight($countryId, $weight)
    {
        return $this->shippingMethods->findByCountryId($countryId)->map(function ($item) use ($countryId, $weight) {
            $item->subtotal = $this->getShippingAmount($item->id, $countryId, $weight);

            return $item;
        });
    }

    /**
     * Get shipping amount.
     *
     * @param $shippingMethodId
     * @param $countryId
     * @param $weight
     * @return float
     */
    public function getShippingAmount($shippingMethodId, $countryId, $weight)
    {
        $shippingCountryMethod = DB::table(Config::get('shipping.shipping_country_method_table'))->where([
            'shipping_method_id' => $shippingMethodId,
            'shipping_country_id' => $countryId,
        ])->first();

        $method = $this->shippingMethods->find($shippingMethodId);

        if (!$shippingCountryMethod | !$method) {
            return false;
        }

        $subtotal = $shippingCountryMethod->first_amount;
        if ($weight >= $shippingCountryMethod->first_weight) {
            $weight -= $shippingCountryMethod->first_weight;
            $subtotal += ceil($weight / $shippingCountryMethod->step_weight) * $shippingCountryMethod->step_amount;
        }

        $subtotal += $shippingCountryMethod->extra_amount;
        $subtotal *= 1 - $method->discount / 100;
        
        return max(0, $subtotal);
    }
}