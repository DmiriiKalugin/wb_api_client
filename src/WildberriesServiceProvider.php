<?php

namespace DmitrijKalugin\WildberriesApiClient;

use Illuminate\Support\ServiceProvider;
use DmitrijKalugin\WildberriesApiClient\Contracts\WildberriesClientInterface;
use DmitrijKalugin\WildberriesApiClient\Http\WildberriesClient;
use DmitrijKalugin\WildberriesApiClient\Services\WildberriesApiService;

class WildberriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/wildberries.php', 'wildberries'
        );

        $this->app->singleton(WildberriesClientInterface::class, function ($app) {
            $config = $app['config']['wildberries'];
            
            $httpConfig = $config['http'] ?? [];
            if (isset($config['token'])) {
                $httpConfig['token'] = $config['token'];
            }

            $client = new WildberriesClient($httpConfig);
            
            // Set base URLs based on sandbox mode
            if ($config['sandbox'] ?? false) {
                $client->setBaseUrls($config['base_urls']['sandbox']);
            } else {
                $client->setBaseUrls($config['base_urls']['production']);
            }

            return $client;
        });

        $this->app->singleton('wildberries', function ($app) {
            return new WildberriesApiService($app[WildberriesClientInterface::class]);
        });

        $this->app->alias('wildberries', WildberriesApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/wildberries.php' => config_path('wildberries.php'),
        ], 'wildberries-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                // Add Artisan commands here if needed
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            WildberriesClientInterface::class,
            'wildberries',
            WildberriesApiService::class,
        ];
    }
}
