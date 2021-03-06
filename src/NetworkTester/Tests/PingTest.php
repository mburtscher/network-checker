<?php

namespace NetworkTester\Tests;

class PingTest extends ProcessTest
{
    private $ip;

    public function __construct($ip)
    {
        $this->ip = $ip;

        parent::__construct("/bin/ping -c 1 -W 3 {$this->ip}");
    }

    function getName()
    {
        return "Ping";
    }

    function getEndpoint()
    {
        return $this->ip;
    }
}
