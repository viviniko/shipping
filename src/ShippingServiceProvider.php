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

        $this->registerShippingService();

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
            \Viviniko\Shipping\Repositories\ShippingMethod\ShippingMethodRepository::class,
            \Viviniko\Shipping\Repositories\ShippingMethod\EloquentShippingMethod::class
        );
        $this->app->singleton(
            \Viviniko\Shipping\Repositories\ShippingCountryMethod\ShippingCountryMethodRepository::class,
            \Viviniko\Shipping\Repositories\ShippingCountryMethod\EloquentShippingCountryMethod::class
        );
    }

    /**
     * Register the shipping service provider.
     *
     * @return void
     */
    protected function registerShippingService()
    {
        $this->app->singleton(
            \Viviniko\Shipping\Services\ShippingService::class,
            \Viviniko\Shipping\Services\ShippingServiceImpl::class
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
            \Viviniko\Shipping\Services\ShippingService::class,
        ];
    }
}