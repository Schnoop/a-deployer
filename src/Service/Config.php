<?php

namespace Antwerpes\ADeployer\Service;

/**
 * Class ConfigFile
 *
 * @package Antwerpes\ADeployer\Service
 */
class Config
{

    /**
     * Config values
     *
     * @var array
     */
    protected $config = [];

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns array with known deployment targets.
     *
     * @return array
     */
    public function getAvailableTargets()
    {
        return array_keys($this->config);
    }

    /**
     * Returns true if $target is known
     *
     * @param string $target
     *
     * @return boolean
     */
    public function isAvailableTarget($target)
    {
        return in_array($target, $this->getAvailableTargets());
    }

}