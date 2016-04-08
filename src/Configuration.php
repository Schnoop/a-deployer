<?php

namespace Antwerpes\ADeployer;

/**
 * Class Configuration
 *
 * @package Antwerpes\ADeployer
 */
class Configuration
{

    /**
     * @var array
     */
    protected $configuration = [
        'ini_file' => 'a-deployer.ini' // Name of configuration file.
    ];

    /**
     * Get config variable.
     *
     * @param string $name Name of config var to get.
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get($name, $default = null)
    {
        if (isset($this->configuration[$name]) === false) {
            return $default;
        }
        return $this->configuration[$name];
    }

}