<?php

namespace Project\Tests;

use Project\Service;

class ServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testCompute()
    {
        $service = new Service(new \Project\Provider());
        $this->assertEquals(42, $service->compute());
    }

}