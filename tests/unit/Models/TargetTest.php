<?php

use Antwerpes\ADeployer\Model\Target;

/**
 * Class TargetTest
 */
class TargetTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test that it is not a critial deployment with some "boolean" and integer values.
     */
    public function testIsNotACriticalDeployment()
    {
        $config = array('critical' => 0);
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = array('critical' => "0");
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = array('critical' => "false");
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = array('critical' => false);
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());

        $config = array();
        $target = new Target('demo', $config);
        $this->assertFalse($target->isCriticalDeployment());
    }

    /**
     * Test that it is a critial deployment with some "boolean" and integer values.
     */
    public function testIsACriticalDeployment()
    {
        $config = array('critical' => 1);
        $target = new Target('demo', $config);
        $this->assertTrue($target->isCriticalDeployment());

        $config = array('critical' => "1");
        $target = new Target('demo', $config);
        $this->assertTrue($target->isCriticalDeployment());

        $config = array('critical' => true);
        $target = new Target('demo', $config);
        $this->assertTrue($target->isCriticalDeployment());
    }

    /**
     * Test that target has password if password is given
     */
    public function testThatTargetHasPasswordWhenPasswordGiven()
    {
        $config = array('server' => ['password' => 'fooBar']);
        $target = new Target('demo', $config);
        $this->assertTrue($target->hasPassword());
    }

    /**
     * Test that target has password if privateKey is given
     */
    public function testThatTargetHasPasswordWhenPrivateKeyGiven()
    {
        $config = array('server' => ['privateKey' => 'fooBar']);
        $target = new Target('demo', $config);
        $this->assertTrue($target->hasPassword());
    }

    /**
     * Test that target has password if privateKey is given
     */
    public function testThatTargetHasNotAPasswordWhenNoPasswordAndNoPrivateKeyExists()
    {
        $config = array('server' => []);
        $target = new Target('demo', $config);
        $this->assertFalse($target->hasPassword());
    }

    public function testThatExcludesReturnedIfGiven()
    {
        $excludes = array('foo', 'bar');
        $config = array('exclude' => $excludes);
        $target = new Target('demo', $config);
        $this->assertEquals($excludes, $target->getExcludes());
    }

    public function testThatExcludesReturnedEmptyArrayIfNotGiven()
    {
        $excludes = array();
        $config = array();
        $target = new Target('demo', $config);
        $this->assertEquals($excludes, $target->getExcludes());
    }

    public function testThatIncludesReturnedIfGiven()
    {
        $includes = array('foo', 'bar');
        $config = array('include' => $includes);
        $target = new Target('demo', $config);
        $this->assertEquals($includes, $target->getIncludes());
    }

    public function testThatIncludesReturnedEmptyArrayIfNotGiven()
    {
        $includes = array();
        $config = array();
        $target = new Target('demo', $config);
        $this->assertEquals($includes, $target->getIncludes());
    }
}