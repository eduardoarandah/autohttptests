<?php

namespace EduardoArandaH\AutoHttpTests\app\Http\Middleware;

use Closure;
use EduardoArandaH\AutoHttpTests\TestGenerator;
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
        // do nothing in handle, only in terminate
        return $next($request);
    }

    /**
     * Analyze request after sent to the browser
     * https://laravel.com/docs/8.x/middleware#terminable-middleware
     */
    public function terminate($request, $response)
    {
        // if command isn't running, ignore
        if (!$this->isCommandRunning()) {
            return;
        }

        //create generator
        $testGenerator = new TestGenerator();
        $test = $testGenerator->generate($request, $response);

        if ($test) {
            $filePath = storage_path("autohttptests.txt");
            file_put_contents($filePath, $test . "\n", FILE_APPEND | LOCK_EX);
        }
    }
    public function isCommandRunning()
    {
        // TODO: launch the command and check if it's running
        // (new \EduardoArandaH\AutoHttpTests\app\Http\Middleware\AutoHttpTests())->isCommandRunning();

        // check processes
        $process = new Process(["ps", "ax"]);
        $process->run();

        // if failed, return
        if (!$process->isSuccessful()) {
            return false;
        }

        // check if command is running
        $output = $process->getOutput();
        return str_contains($output, "autohttptest:create");
    }
}
