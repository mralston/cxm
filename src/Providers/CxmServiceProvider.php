<?php

namespace Mralston\Cxm\Providers;

use Illuminate\Support\ServiceProvider;
use Mralston\Cxm\ApiClient;

class CxmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'cxm');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('cxm', function ($app) {
            return new ApiClient(
                clientId: config('cxm.client_id'),
                secret: config('cxm.secret'),
                token: config('cxm.token'),
                endpoint: config('cxm.endpoint'),
            );
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/config.php' => config_path('cxm.php'),
            ], 'cxm-config');
        }
    }
}
