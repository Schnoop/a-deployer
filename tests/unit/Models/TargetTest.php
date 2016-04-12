<?php

use Antwerpes\ADeployer\Model\Target;

/**
 * Class TargetTest.
 */
class TargetTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that it is not a critial deployment with some "boolean" and integer values.
     */
    public function testIsNotACriticalDeployment()
    {
        $config = ['critical' => 0];
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = ['critical' => '0'];
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = ['critical' => 'false'];
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = ['critical' => false];
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = [];
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());
    }

    /**
     * Test that it is a critial deployment with some "boolean" and integer values.
     */
    public function testIsACriticalDeployment()
    {
        $config = ['critical' => 1];
        $target = new Target('demo', $config);
        $this->assertTrue($target->isCriticalDeployment());

        $config = ['critical' => '1'];
        $target = new Target('demo', $config);
        $this->assertTrue($target->isCriticalDeployment());

        $config = ['critical' => true];
        $target = new Target('demo', $config);
        $this->assertTrue($target->isCriticalDeployment());
    }

    /**
     * Test that target has password if password is given.
     */
    public function testThatTargetHasPasswordWhenPasswordGiven()
    {
        $config = ['server' => ['password' => 'fooBar']];
        $target = new Target('demo', $config);
        $this->assertTrue($target->hasPassword());
    }

    /**
     * Test that target has password if privateKey is given.
     */
    public function testThatTargetHasPasswordWhenPrivateKeyGiven()
    {
        $config = ['server' => ['privateKey' => 'fooBar']];
        $target = new Target('demo', $config);
        $this->assertTrue($target->hasPassword());
    }

    /**
     * Test that target has password if privateKey is given.
     */
    public function testThatTargetHasNotAPasswordWhenNoPasswordAndNoPrivateKeyExists()
    {
        $config = ['server' => []];
        $target = new Target('demo', $config);
        $this->assertFalse($target->hasPassword());
    }

    public function testThatExcludesReturnedIfGiven()
    {
        $excludes = ['foo', 'bar'];
        $config = ['exclude' => $excludes];
        $target = new Target('demo', $config);
        $this->assertEquals($excludes, $target->getExcludes());
    }

    public function testThatExcludesReturnedEmptyArrayIfNotGiven()
    {
        $excludes = [];
        $config = [];
        $target = new Target('demo', $config);
        $this->assertEquals($excludes, $target->getExcludes());
    }

    public function testThatIncludesReturnedIfGiven()
    {
        $includes = ['foo', 'bar'];
        $config = ['include' => $includes];
        $target = new Target('demo', $config);
        $this->assertEquals($includes, $target->getIncludes());
    }

    public function testThatIncludesReturnedEmptyArrayIfNotGiven()
    {
        $includes = [];
        $config = [];
        $target = new Target('demo', $config);
        $this->assertEquals($includes, $target->getIncludes());
    }

    public function testName()
    {
        $config = [];
        $target = new Target('demo', $config);
        $this->assertEquals('demo', $target->getName());
    }

    public function testSetPassword()
    {
        $config = [];
        $target = new Target('demo', $config);
        $target->setPassword('fooBar');
        $this->assertEquals('fooBar', $target['server']['password']);
    }
}
