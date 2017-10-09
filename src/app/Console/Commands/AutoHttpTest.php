<?php

namespace EduardoArandaH\AutoHttpTests\app\Console\Commands;

use Illuminate\Console\Command;

class AutoHttpTest extends Command
{
    protected $signature = 'autohttptest:create';
    protected $description = 'Starts the recording of a test';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filePath = storage_path('autohttptests.txt');

        $name = ucfirst($this->ask('Name your test example: HomePage'));

        //determine final path
        $finalPath = base_path("tests/Feature/{$name}Test.php");

        //test already exist?
        if (file_exists($finalPath)) {
            $this->line("Sorry, that test already exists in $finalPath");
            return;
        }

        //start template
        file_put_contents($filePath, $this->startTemplate($name));

        $this->line("Recording...");

        //finish and save
        if ($this->confirm('Save recording?') && file_exists($filePath)) {

            //finish template
            file_put_contents($filePath, $this->finishTemplate(), FILE_APPEND | LOCK_EX);

            //move to its final destination
            if (!file_exists(base_path("tests/Feature"))) {
                mkdir(base_path("tests/Feature"));
            }
            rename($filePath, $finalPath);

            //tell the user
            $this->line("Test saved as $finalPath");
            return;

        } else {

            //if cancelled, delete
            unlink($filePath);
        }
    }
    public function startTemplate($name)
    {
        return "<?php

namespace Tests\Feature;
use Tests\TestCase;

class {$name}Test extends TestCase
{
    public function testAutoHttpTest()
    {";
    }
    public function finishTemplate()
    {
        return "\t}\n}";
    }

}
