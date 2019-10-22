<?php

namespace Viviniko\Shipping;

use Viviniko\Shipping\Console\Commands\ShippingTableCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ShippingServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/shipping.php' => config_path('shipping.php'),
        ]);

        // Register commands
        $this->commands('command.shipping.table');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/shipping.php', 'shipping');

        $this->registerRepositories();

        $this->registerCommands();
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.shipping.table', function ($app) {
            return new ShippingTableCommand($app['files'], $app['composer']);
        });
    }

    protected function registerRepositories()
    {
        $this->app->singleton(
            \Viviniko\Shipping\Repositories\Method\MethodRepository::class,
            \Viviniko\Shipping\Repositories\Method\EloquentMethod::class
        );
        $this->app->singleton(
            \Viviniko\Shipping\Repositories\Freight\FreightRepository::class,
            \Viviniko\Shipping\Repositories\Freight\EloquentFreight::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }
}