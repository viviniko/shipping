<?php

namespace Viviniko\Shipping\Repositories\ShippingCountryMethod;

interface ShippingCountryMethodRepository
{
    /**
     * Find data by field and value
     *
     * @param $column
     * @param null $value
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function findAllBy($column, $value = null, $columns = ['*']);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function exists($column, $value = null);
}