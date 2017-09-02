<?php

namespace Viviniko\Shipping\Console\Commands;

use Viviniko\Support\Console\CreateMigrationCommand;

class ShippingTableCommand extends CreateMigrationCommand
{
    /**
     * @var string
     */
    protected $name = 'shipping:table';

    /**
     * @var string
     */
    protected $description = 'Create a migration for the shipping service table';

    /**
     * @var string
     */
    protected $stub = __DIR__.'/stubs/shipping.stub';

    /**
     * @var string
     */
    protected $migration = 'create_shipping_table';
}
