<?php

namespace Antyki\Container;

use DI\ContainerBuilder;
use function DI\factory;
use Psr\Container\ContainerInterface;

use PHPUnit\Framework\TestCase;

final class MainTest extends TestCase
{
    protected $instance;

    protected function setUp() : void
    {
        $this->instance = new Main();
    }

    public function testTest()
    {
        $this->assertTrue($this->instance->test());
    }

}
