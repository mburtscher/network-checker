<?php

namespace NetworkTester\Tests;

class LookupTest extends ProcessTest
{
    private $domain;
    private $server;

    public function __construct($domain, $server)
    {
        $this->domain = $domain;
        $this->server = $server;

        parent::__construct("nslookup {$domain} {$server}");
    }

    function getName()
    {
        return "DNS Lookup";
    }

    function getEndpoint()
    {
        return "{$this->domain}/{$this->server}";
    }
}
