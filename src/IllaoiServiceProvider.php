<?php

namespace Laravelista\Illaoi;

use Laravelista\Illaoi\Illaoi;
use Illuminate\Support\ServiceProvider;

class IllaoiServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('illaoi', function()
        {
            return new Illaoi();
        });
    }
}
