<?php


namespace GD;

use Silly\Edition\Pimple\Application;
use Symfony\Component\Process\Process;

class RunTest extends BaseGherkinToDusk
{


    /**
     * @var string
     */
    protected $command;

    protected $path_to_command;

    public function handleBrowser($path)
    {
        $this->context = 'browser';
        $this->setPathToCommand('php artisan dusk');
        $this->runTestFromFile($path);
    }

    public function handleDomain($path)
    {
        $this->context = 'domain';
        $this->setPathToCommand('vendor/bin/phpunit');
        $this->runTestFromFile($path);
    }


    private function runTestFromFile($path)
    {
        $this->setPathToFeature($path)->buildDuskTestName();

        $path_to_test = $this->fullPathToDestinationFile();

        $path_to_command = $this->getPathToCommand();

        $this->setCommand(sprintf("%s %s", $path_to_command, $path_to_test));

        $this->runCommand();
    }

    protected function runCommand()
    {
        $process = new \Symfony\Component\Process\Process($this->command);

        $process->setTimeout(600);

        $process->start();

        $process->wait(function ($type, $buffer) use ($process) {
            if (Process::ERR === $type) {
                $this->app->getContainer()['output']->write($buffer);
            } else {
                $this->app->getContainer()['output']->write($buffer);
            }
        });

        if (!$process->isSuccessful()) {
            //throw new ProcessFailedException($process);
            $this->app->getContainer()['output']->writeln("<error>Error with tests....</error>");
            exit(1);
        } else {
            $this->app->getContainer()['output']->writeln("<fg=black;bg=green>All Tests Passed...</>");
        }
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getPathToCommand()
    {
        return $this->path_to_command;
    }

    /**
     * @param mixed $path_to_command
     */
    public function setPathToCommand($path_to_command)
    {
        $this->path_to_command = $path_to_command;
    }
}
