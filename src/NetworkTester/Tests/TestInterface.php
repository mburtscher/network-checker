<?php

namespace NetworkTester\Tests;

interface TestInterface
{
    const STATUS_RUNNING = "running";
    const STATUS_OK = "ok";
    const STATUS_FAIL = "fail";

    function start();
    function getStatus();
    function getName();
    function getEndpoint();
}
