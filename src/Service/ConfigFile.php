<?php

namespace Antwerpes\ADeployer\Service;

use Noodlehaus\Config;

/**
 * Class ConfigFile
 *
 * @package Antwerpes\ADeployer\Service
 */
class ConfigFile
{

    const NAME = 'a-deployer.ini';

    protected $data = "; NOTE: If non-alphanumeric characters are present, enclose in value in quotes.\n
[staging]
quickmode = ftp://example:password@production-example.com:21/path/to/installation\n
[production]
scheme = sftp
user = example
pass = password
host = staging-example.com
path = /path/to/installation
port = 22";

    /**
     * Returns true if config file exists.
     *
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->getFullPath());
    }

    /**
     * Creates a config file with sample content
     *
     * @return mixed
     */
    public function create()
    {
        return file_put_contents($this->getFullPath(), $this->data);
    }

    /**
     * Returns full path to config file
     *
     * @return string
     */
    protected function getFullPath()
    {
        return getcwd() . DIRECTORY_SEPARATOR . self::NAME;
    }

    /**
     * Open config file and return instance.
     *
     * @return Config
     */
    protected function loadConfigFile()
    {
        return new Config($this->getFullPath());
    }

    /**
     * Returns an array with all known deployment targets.
     *
     * @return array
     */
    public function getTargets()
    {
        return array_keys($this->loadConfigFile()->all());
    }

}