<?php

namespace Antwerpes\ADeployer\Model;

/**
 * Class Target
 *
 * @package Antwerpes\ADeployer\Model
 */
class Target implements \ArrayAccess
{

    /**
     * Config values
     *
     * @var array
     */
    protected $config = [];

    /**
     * Name of target
     *
     * @var string
     */
    protected $name;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    /**
     * Returns true if server is configured for critical
     *
     * @return bool
     */
    public function isCritialDeployment()
    {
        return isset($this->config['critical']) && $this->config['critical'] == 1;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->config['server']['password'] = $password;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->config[] = $value;
        } else {
            $this->config[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }

    /**
     * Returns true if password for server has been set.
     *
     * @return bool
     */
    public function hasPassword()
    {
        return isset($this->config['server']['password']) === true || isset($this->config['server']['privateKey']) === true;
    }

    /**
     * Returns name of target
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns array with files to exclude
     *
     * @return array
     */
    public function getExcludes()
    {
        if (isset($this->config['exclude']) === true) {
            return $this->config['exclude'];
        }
        return array();
    }

    /**
     * Returns array with files to include
     *
     * @return array
     */
    public function getIncludes()
    {
        if (isset($this->config['include']) === true) {
            return $this->config['include'];
        }
        return array();
    }

}