<?php

namespace Viviniko\Shipping\Repositories\ShippingCountryMethod;

use Viviniko\Support\ValidatesData;

trait ValidatesShippingCountryMethodData
{
    use ValidatesData;

    public function validateCreateData($data)
    {
        $this->validate($data, $this->rules());
    }

    public function validateUpdateData($methodId, $data)
    {
        $this->validate($data, $this->rules($methodId));
    }

    public function rules($methodId = null)
    {
        return [
            'group' => 'required',
            'shipping_country_id' => 'required',
            'first_amount' => 'required',
            'first_weight' => 'required',
            'step_amount' => 'required',
            'step_weight' => 'required',
            'extra_amount' => 'required',
        ];
    }
}