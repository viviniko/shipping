<?php

namespace Viviniko\Shipping\Models;

use Illuminate\Support\Facades\Config;
use Viviniko\Support\Database\Eloquent\Model;

class Method extends Model
{
    protected $tableConfigKey = 'shipping.methods_table';

    protected $fillable = [
        'name', 'description', 'times', 'discounts', 'is_active', 'sort'
    ];

    public function freights()
    {
        return $this->hasMany(Config::get('shipping.freight'));
    }
}