<?php

use Antwerpes\ADeployer\Model\Transfer;

/**
 * Class TransferTest.
 */
class TransferTest extends PHPUnit_Framework_TestCase
{
    public function testAddFileToUpload()
    {
        $transfer = new Transfer();
        $transfer->addFileToUpload('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToUpload());
        $transfer->addFileToUpload('bar');
        $this->assertEquals(['foo', 'bar'], $transfer->getFilesToUpload());
    }

    public function testAddFileToDelete()
    {
        $transfer = new Transfer();
        $transfer->addFileToDelete('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToDelete());
        $transfer->addFileToDelete('bar');
        $this->assertEquals(['foo', 'bar'], $transfer->getFilesToDelete());
    }

    public function testAddFileToSkip()
    {
        $transfer = new Transfer();
        $transfer->addFileToSkip('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToSkip());
        $transfer->addFileToSkip('bar');
        $this->assertEquals(['foo', 'bar'], $transfer->getFilesToSkip());
    }

    public function testSetFilesToUpload()
    {
        $transfer = new Transfer();
        $transfer->addFileToUpload('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToUpload());
        $transfer->setFilesToUpload(['bar']);
        $this->assertEquals(['bar'], $transfer->getFilesToUpload());
    }

    public function testSetFilesToDelete()
    {
        $transfer = new Transfer();
        $transfer->addFileToDelete('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToDelete());
        $transfer->setFilesToDelete(['bar']);
        $this->assertEquals(['bar'], $transfer->getFilesToDelete());
    }

    public function testSetFileToSkip()
    {
        $transfer = new Transfer();
        $transfer->addFileToSkip('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToSkip());
        $transfer->setFilesToSkip(['bar']);
        $this->assertEquals(['bar'], $transfer->getFilesToSkip());
    }

    public function testAddFilesToUpload()
    {
        $transfer = new Transfer();
        $transfer->addFileToUpload('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToUpload());
        $transfer->addFileToUpload('bar');
        $this->assertEquals(['foo', 'bar'], $transfer->getFilesToUpload());
        $transfer->addFilesToUpload(['fooBar', 'barFoo']);
        $this->assertEquals(['foo', 'bar', 'fooBar', 'barFoo'], $transfer->getFilesToUpload());
    }

    public function testAddFilesToUploadWithSameName()
    {
        $transfer = new Transfer();
        $transfer->addFileToUpload('foo');
        $this->assertEquals(['foo'], $transfer->getFilesToUpload());
        $transfer->addFileToUpload('bar');
        $this->assertEquals(['foo', 'bar'], $transfer->getFilesToUpload());
        $transfer->addFilesToUpload(['foo', 'barFoo']);
        $this->assertEquals(['foo', 'bar', 'foo', 'barFoo'], $transfer->getFilesToUpload());
    }
}
