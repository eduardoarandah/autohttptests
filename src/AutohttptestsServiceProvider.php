<?php

namespace Eduardoarandah\Autohttptests;

use Eduardoarandah\Autohttptests\app\Console\Commands\AutoHttpTest;
use Eduardoarandah\Autohttptests\app\Http\Middleware\AutoHttpTests;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class AutohttptestsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //register middleware
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware(AutoHttpTests::class);

        //register command
        if ($this->app->runningInConsole()) {
            $this->commands([
                AutoHttpTest::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
