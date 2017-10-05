<?php

namespace Eduardoarandah\Autohttptests\app\Http\Middleware;

use Closure;
use Eduardoarandah\Autohttptests\TestGenerator;
use Symfony\Component\Process\Process;

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
        $filePath=storage_path('autohttptests.txt');

        if (file_exists($filePath)) {

            //check if command is running
            if ($this->commandIsRunning()) {

                //create generator
                $testGenerator = new TestGenerator();
                $test          = $testGenerator->generate($request, $response);

                if ($test) {
                    file_put_contents($filePath, $test . "\n", FILE_APPEND | LOCK_EX);
                }
            }

        }
    }
    public function commandIsRunning()
    {
        $process = new Process('ps ax | grep autohttptest:create | grep -v grep');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            return false;
        }

        return $process->getOutput() ? true : false;

    }
}
