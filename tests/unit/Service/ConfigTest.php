<?php
use Antwerpes\ADeployer\Service\Config;

/**
 * Class ConfigTest
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{

    public function testThatTargetIsAvailable()
    {
        $config = array(
            'foo' => array()
        );
        $service = new Config($config);
        $this->assertTrue($service->isAvailableTarget('foo'));
    }

    public function testThatTargetIsNotAvailable()
    {
        $config = array(
            'bar' => array()
        );
        $service = new Config($config);
        $this->assertFalse($service->isAvailableTarget('foo'));
    }

    public function testGetAvailableTargets()
    {
        $config = array(
            'bar' => array(),
            'foo' => array(),
        );
        $service = new Config($config);
        $this->assertEquals(['bar', 'foo'], $service->getAvailableTargets());
    }

    public function testGetConfigForTargetWhenAvailable()
    {
        $config = array(
            'bar' => array(),
            'foo' => array(),
        );
        $service = new Config($config);
        $this->assertInstanceOf('Antwerpes\ADeployer\Model\Target', $service->getConfigForTarget('bar'));
    }

    public function testGetConfigForTargetWhenNotAvailable()
    {
        $config = array(
            'bar' => array(),
            'foo' => array(),
        );
        $service = new Config($config);
        $this->assertNull($service->getConfigForTarget('fooBar'));
    }
}