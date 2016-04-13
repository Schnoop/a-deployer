<?php

/**
 * Class ConnectionTest.
 */
class ConnectionTest extends PHPUnit_Framework_TestCase
{
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
}
