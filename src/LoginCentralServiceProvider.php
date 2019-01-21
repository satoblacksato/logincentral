<?php

namespace Eliberio\LoginCentral;

use Illuminate\Support\ServiceProvider;

class LoginCentralServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../logincentral.php' => config_path('logincentral.php')
        ], 'login-central-config');

    }

    public $optionsLogin=null;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->app->make('Eliberio\LoginCentral\Controllers\ProcessLoginController');
        $this->mergeConfigFrom(
            __DIR__ . '/../logincentral.php', 'logincentral'
        );
    }
}
