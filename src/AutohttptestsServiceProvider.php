<?php

namespace Eduardoarandah\Autohttptests;

use Eduardoarandah\Autohttptests\app\Http\Middleware\AutoHttpTests;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class AutohttptestsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware(AutoHttpTests::class);        
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
