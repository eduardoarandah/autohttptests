<?php

namespace Eduardoarandah\Autohttptests\app\Http\Middleware;

use Closure;
use Eduardoarandah\Autohttptests\TestGenerator;

class AutoHttpTests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
    public function terminate($request, $response)
    {

        $generator = new TestGenerator();
        $test      = $generator->generateTest($request, $response);

        file_put_contents(storage_path('autohttptests.txt'), $test . "\n", FILE_APPEND | LOCK_EX);

    }
}
