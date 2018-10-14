<?php

namespace Viviniko\Shipping\Services;

use Viviniko\Country\Services\CountryService;
use Viviniko\Shipping\Repositories\ShippingMethod\ShippingMethodRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ShippingServiceImpl implements ShippingService
{
    protected $shippingMethods;

    protected $countryService;

    public function __construct(ShippingMethodRepository $shippingMethods, CountryService $countryService)
    {
        $this->shippingMethods = $shippingMethods;
        $this->countryService = $countryService;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethodsByCountryAndWeight($country, $weight)
    {
        $countryId = $this->getCountryIdByCode($country);

        return $this->shippingMethods->findByCountryId($countryId)->map(function ($item) use ($country, $weight) {
            $item->subtotal = $this->getShippingAmount($item->id, $country, $weight);

            return $item;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAmount($shippingMethodId, $country, $weight)
    {
        $countryId = $this->getCountryIdByCode($country);

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

    protected function getCountryIdByCode($code)
    {
        $country = $this->countryService->findByCode($code);
        if (!$country) {
            throw new \Exception("Country not found - '$code'");
        }

        return $country->id;
    }
}