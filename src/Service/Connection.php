<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Target;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * Class Connection.
 */
class Connection
{
    /**
     * Get connection.
     *
     * @param Target $config
     *
     * @throws RuntimeException
     *
     * @return Filesystem
     */
    public function getConnection(Target $config)
    {
        if (isset($config['server']) === false) {
            throw new RuntimeException('No server config found.');
        }

        $method = 'create'.ucfirst(strtolower($config['server']['scheme'])).'Connection';
        if (method_exists($this, $method) === false) {
            throw new RuntimeException('Unsupported connection scheme: '.$config['server']['scheme']);
        }

        $connection = $this->{$method}($config['server']);
        $connection->connect();

        return new Filesystem($connection);
    }

    /**
     * Create a SFTP connection and return FileSystem object with that connection.
     *
     * @param array $config
     *
     * @return SftpAdapter
     */
    public function createSftpConnection(array $config)
    {
        return new SftpAdapter($config);
    }

    /**
     * Create a FTP connection and return FileSystem object with that connection.
     *
     * @param array $config
     *
     * @return Ftp
     */
    public function createFtpConnection(array $config)
    {
        return new Ftp($config);
    }
}
