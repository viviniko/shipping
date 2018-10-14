<?php

namespace Viviniko\Shipping\Services;

use Viviniko\Currency\Amount;
use Viviniko\Shipping\Repositories\ShippingCountryMethod\ShippingCountryMethodRepository;
use Viviniko\Shipping\Repositories\ShippingMethod\ShippingMethodRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ShippingServiceImpl implements ShippingService
{
    protected $shippingMethods;

    protected $shippingCountryMethods;

    public function __construct(ShippingMethodRepository $shippingMethods, ShippingCountryMethodRepository $shippingCountryMethods)
    {
        $this->shippingMethods = $shippingMethods;
        $this->shippingCountryMethods = $shippingCountryMethods;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethod($id)
    {
        return $this->shippingMethods->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function shippingMethods()
    {
        return $this->shippingMethods->all();
    }

    /**
     * {@inheritdoc}
     */
    public function createShippingMethod(array $data)
    {
        return $this->shippingMethods->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateShippingMethod($id, array $data)
    {
        return $this->shippingMethods->update($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteShippingMethod($id)
    {
        return $this->shippingMethods->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingCountryMethodsByShippingMethodId($id)
    {
        return $this->shippingCountryMethods->findAllBy('method_id', $id);
    }

    /**
     * {@inheritdoc}
     */
    public function createShippingCountryMethod(array $data)
    {
        return $this->shippingCountryMethods->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateShippingCountryMethod($id, array $data)
    {
        return $this->shippingCountryMethods->update($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteShippingCountryMethod($id)
    {
        return $this->shippingCountryMethods->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethodsByCountryAndWeight($country, $weight)
    {
        return $this->shippingMethods->findByCountry($country)->map(function ($item) use ($country, $weight) {
            $item->subtotal = $this->getShippingAmount($item->id, $country, $weight);

            return $item;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAmount($shippingMethodId, $country, $weight)
    {
        $shippingCountryMethod = DB::table(Config::get('shipping.shipping_country_method_table'))->where([
            'method_id' => $shippingMethodId,
            'country' => $country,
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
        
        return Amount::createBaseAmount(max(0, $subtotal));
    }
}