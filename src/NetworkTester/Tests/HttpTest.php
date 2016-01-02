<?php

namespace NetworkTester\Tests;

class HttpTest extends ProcessTest
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;

        parent::__construct("curl {$this->url}");
    }

    function getName()
    {
        return "HTTP";
    }

    function getEndpoint()
    {
        return parse_url($this->url, PHP_URL_HOST);
    }
}
