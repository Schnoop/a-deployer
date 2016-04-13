<?php

/**
 * Class FilterTest.
 */
class FilterTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testFilter()
    {
        $filter = new \Antwerpes\ADeployer\Service\Filter(['fooBar']);

        $transfer = new \Antwerpes\ADeployer\Model\Transfer();
        $transfer->setFilesToUpload(['fooBar', 'barFoo']);
        $transfer->setFilesToDelete(['barBar', 'fooBar', 'fooFoo']);

        $result = $filter->filter($transfer);

        $this->assertEquals([1 => 'barFoo'], $result->getFilesToUpload());
        $this->assertEquals([0 => 'barBar', 2 => 'fooFoo'], $result->getFilesToDelete());
        $this->assertEquals(['fooBar', 'fooBar'], $result->getFilesToSkip());
    }

}
