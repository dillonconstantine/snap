<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests;

use PHPUnit\Framework\MockObject\MockObject;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $className
     * @param array|string[] $methods
     * @param array|mixed[] $constructorArgs
     * @return MockObject
     */
    protected function getMock(string $className, array $methods = [], array $constructorArgs = []): MockObject
    {
        return $this->getMockBuilder($className)
            ->onlyMethods($methods)
            ->setConstructorArgs($constructorArgs)
            ->getMock();
    }
}
