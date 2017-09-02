<?php

namespace Viviniko\Shipping\Repositories\ShippingMethod;

use Viviniko\Support\ValidatesData;

trait ValidatesShippingMethodData
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
            'name' => 'required',
            'description' => 'required',
            'times' => 'required',
            'discount' => 'required',
        ];
    }
}