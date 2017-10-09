<?php

namespace EduardoArandaH\AutoHttpTests;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class AutoHttpTestsServiceProvider extends ServiceProvider
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
        $kernel->pushMiddleware(\EduardoArandaH\AutoHttpTests\app\Http\Middleware\AutoHttpTests::class);

        //register command
        if ($this->app->runningInConsole()) {
            $this->commands([                
                \EduardoArandaH\AutoHttpTests\app\Console\Commands\AutoHttpTest::class
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
