<?php

namespace Viviniko\Shipping\Models;

use Illuminate\Support\Facades\Config;
use Viviniko\Support\Database\Eloquent\Model;

class Freight extends Model
{
    protected $tableConfigKey = 'shipping.freights_table';

    protected $fillable = [
        'group', 'method_id', 'country', 'first_amount', 'first_weight', 'step_amount', 'step_weight', 'extra_amount'
    ];

    public function shippingMethod()
    {
        return $this->belongsTo(Config::get('shipping.method'), 'method_id');
    }
}