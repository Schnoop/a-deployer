<?php

use Antwerpes\ADeployer\Service\Config;

/**
 * Class ConfigTest.
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testThatTargetIsAvailable()
    {
        $config = [
            'foo' => [],
        ];
        $service = new Config($config);
        $this->assertTrue($service->isAvailableTarget('foo'));
    }

    public function testThatTargetIsNotAvailable()
    {
        $config = [
            'bar' => [],
        ];
        $service = new Config($config);
        $this->assertFalse($service->isAvailableTarget('foo'));
    }

    public function testGetAvailableTargets()
    {
        $config = [
            'bar' => [],
            'foo' => [],
        ];
        $service = new Config($config);
        $this->assertEquals(['bar', 'foo'], $service->getAvailableTargets());
    }

    public function testGetConfigForTargetWhenAvailable()
    {
        $config = [
            'bar' => [],
            'foo' => [],
        ];
        $service = new Config($config);
        $this->assertInstanceOf('Antwerpes\ADeployer\Model\Target', $service->getConfigForTarget('bar'));
    }

    public function testGetConfigForTargetWhenNotAvailable()
    {
        $config = [
            'bar' => [],
            'foo' => [],
        ];
        $service = new Config($config);
        $this->assertNull($service->getConfigForTarget('fooBar'));
    }
}
