<?php

namespace Viviniko\Shipping\Services;

use Viviniko\Currency\Amount;
use Viviniko\Shipping\Repositories\Freight\FreightRepository;
use Viviniko\Shipping\Repositories\Method\MethodRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ShippingServiceImpl implements ShippingService
{
    protected $methods;

    protected $freights;

    public function __construct(MethodRepository $methodRepository, FreightRepository $freightRepository)
    {
        $this->methods = $methodRepository;
        $this->freights = $freightRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethod($id)
    {
        return $this->methods->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function shippingMethods()
    {
        return $this->methods->all();
    }

    /**
     * {@inheritdoc}
     */
    public function createShippingMethod(array $data)
    {
        return $this->methods->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateShippingMethod($id, array $data)
    {
        return $this->methods->update($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteShippingMethod($id)
    {
        throw_if($this->freights->exists('method_id', $id), new \Exception("The shipping country method is not empty."));
        return $this->methods->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingCountryMethod($id)
    {
        return $this->freights->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingCountryMethodsByMethodId($id)
    {
        return $this->freights->findAllBy('method_id', $id);
    }

    /**
     * {@inheritdoc}
     */
    public function createShippingCountryMethod(array $data)
    {
        return $this->freights->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateShippingCountryMethod($id, array $data)
    {
        return $this->freights->update($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteShippingCountryMethod($id)
    {
        return $this->freights->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethodsByCountryAndWeight($country, $weight)
    {
        $freights = $this->freights->findAllBy('country', $country);

        return $this->methods->findAllBy('id', $freights->map(function ($freight) { return $freight->id; }))
            ->map(function ($item) use ($country, $weight) {
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

        $method = $this->methods->find($shippingMethodId);

        if (!$shippingCountryMethod | !$method) {
            return false;
        }

        $subtotal = $shippingCountryMethod->first_amount;
        if ($weight >= $shippingCountryMethod->first_weight) {
            $weight -= $shippingCountryMethod->first_weight;
            $subtotal += ($shippingCountryMethod->step_weight > 0 ? ceil($weight / $shippingCountryMethod->step_weight) : 1) * $shippingCountryMethod->step_amount;
        }

        $subtotal += $shippingCountryMethod->extra_amount;
        $subtotal *= 1 - $method->discount / 100;
        
        return Amount::createBaseAmount(max(0, $subtotal));
    }
}