<?php

namespace NetworkTester\Tests;

use Symfony\Component\Process\Process;

abstract class ProcessTest implements TestInterface
{
    /**
     * @var Process
     */
    private $process;

    protected function __construct($cmd)
    {
        $this->process = new Process($cmd);
    }

    function start()
    {
        $this->process->start();
    }

    function getStatus()
    {
        if ($this->process->isRunning()) {
            return TestInterface::STATUS_RUNNING;
        } elseif ($this->process->getExitCode() > 0) {
            return TestInterface::STATUS_FAIL;
        } else {
            return TestInterface::STATUS_OK;
        }
    }
}
