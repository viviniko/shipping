<?php

namespace Viviniko\Shipping\Repositories\Freight;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\EloquentRepository;

class EloquentFreight extends EloquentRepository implements FreightRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('shipping.freight'));
    }
}