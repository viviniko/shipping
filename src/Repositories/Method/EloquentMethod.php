<?php

namespace Viviniko\Shipping\Repositories\Method;

use Viviniko\Repository\EloquentRepository;
use Illuminate\Support\Facades\Config;

class EloquentMethod extends EloquentRepository implements MethodRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('shipping.method'));
    }
}