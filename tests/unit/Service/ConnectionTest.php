<?php

/**
 * Class ConnectionTest.
 */
class ConnectionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        # Warning:
        PHPUnit_Framework_Error_Warning::$enabled = false;

        # notice, strict:
        PHPUnit_Framework_Error_Notice::$enabled = false;
    }

    public function testExceptionIfNoServerFound()
    {
        $this->expectException('\Symfony\Component\Console\Exception\RuntimeException');
        $this->expectExceptionMessage('No server config found.');

        $connection = new \Antwerpes\ADeployer\Service\Connection();
        $connection->getConnection(new \Antwerpes\ADeployer\Model\Target('demo', []));
    }

    public function testExceptionIfNoServerSchemeNotSupported()
    {
        $this->expectException('\Symfony\Component\Console\Exception\RuntimeException');
        $this->expectExceptionMessage('Unsupported connection scheme: fooBar');

        $connection = new \Antwerpes\ADeployer\Service\Connection();
        $connection->getConnection(new \Antwerpes\ADeployer\Model\Target('demo', ['server' => ['scheme' => 'fooBar']]));
    }

    public function testGetSftpConnection()
    {
        $connection = new \Antwerpes\ADeployer\Service\Connection();
        $this->assertInstanceOf('\League\Flysystem\Sftp\SftpAdapter', $connection->createSftpConnection([]));
    }

    public function testGetFtpConnection()
    {
        if (defined('FTP_BINARY') === false) {
            define('FTP_BINARY', 2);
        }
        $connection = new \Antwerpes\ADeployer\Service\Connection();
        $this->assertInstanceOf('\League\Flysystem\Adapter\Ftp', $connection->createFtpConnection([]));
    }

/*
    public function testGetConnectionForFtp()
    {
        if (defined('FTP_BINARY') === false) {
            define('FTP_BINARY', 2);
        }

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Could not connect to host: , port:21');

        $target = new \Antwerpes\ADeployer\Model\Target('demo', ['server' => ['scheme' => 'ftp']]);
        $connection = new \Antwerpes\ADeployer\Service\Connection();
        $connection->getConnection($target);
    } */
}
