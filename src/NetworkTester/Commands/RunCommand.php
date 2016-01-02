<?php

namespace NetworkTester\Commands;

use NetworkTester\Tests\HttpTest;
use NetworkTester\Tests\LookupTest;
use NetworkTester\Tests\PingTest;
use NetworkTester\Tests\TestInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName("run")
            ->setDescription("Runs all tests…")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $commands \NetworkTester\Tests\TestInterface[] */
        $commands = [
            new PingTest("192.168.0.1"),
            new PingTest("8.8.8.8"),
            new PingTest("195.58.160.194"),
            new LookupTest("sharelatex.com", "8.8.8.8"),
            new LookupTest("sharelatex.com", "195.58.160.194"),
            new HttpTest("http://google.com"),
        ];

        $output->write(str_repeat(" ", strlen(date("Y-m-d H:i:s"))));
        $previousGroup = null;
        foreach ($commands as $command) {
            if ($command->getName() == $previousGroup) {
                $output->write(str_repeat(" ", $this->getColumnWidth($command) + 3));
            } else {
                $output->write(" | " . str_pad($command->getName(), $this->getColumnWidth($command)));
                $previousGroup = $command->getName();
            }
        }
        $output->write("\n");

        $output->write(str_repeat(" ", strlen(date("Y-m-d H:i:s"))));
        foreach ($commands as $command) {
            $output->write(" | " . str_pad($command->getEndpoint(), $this->getColumnWidth($command)));
        }
        $output->write("\n");

        do {
            $start = microtime(true);

            // Start all commands
            foreach ($commands as $command) {
                $command->start();
            }

            do {
                $output->write("\r" . date("Y-m-d H:i:s"));

                $running = false;
                foreach ($commands as $command) {
                    if ($command->getStatus() == TestInterface::STATUS_RUNNING) {
                        $running = true;
                    }

                    if ($command->getStatus() == TestInterface::STATUS_FAIL) {
                        $prefix = "<error>";
                        $suffix = "</error>";
                    } else {
                        $prefix = "";
                        $suffix = "";
                    }

                    $output->write(" | " . $prefix . str_pad($command->getStatus(), $this->getColumnWidth($command), " ", STR_PAD_RIGHT) . $suffix);
                }

                sleep(1);
            } while ($running);

            // Wait at least 1 sec
            $waitTime = $start + 5000 - microtime(true);
            usleep($waitTime * 1000);

            $output->write("\n");
        } while (true);
    }

    private function getColumnWidth(TestInterface $test)
    {
        return max(strlen($test->getName()), strlen($test->getEndpoint()));
    }
}
