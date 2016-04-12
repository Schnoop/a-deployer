<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Target;

/**
 * Class ConfigFile.
 */
class Config
{
    /**
     * Array with targets.
     *
     * @var Target[]
     */
    protected $targets = [];

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $name => $target) {
            $this->targets[$name] = new Target($name, $target);
        }
    }

    /**
     * Returns true if $target is known.
     *
     * @param string $target
     *
     * @return bool
     */
    public function isAvailableTarget($target)
    {
        return isset($this->targets[$target]);
    }

    /**
     * Returns array with known deployment targets.
     *
     * @return array
     */
    public function getAvailableTargets()
    {
        return array_keys($this->targets);
    }

    /**
     * Returns config for given $target, of empty array if not found.
     *
     * @param string $target
     *
     * @return Target|null
     */
    public function getConfigForTarget($target)
    {
        if (isset($this->targets[$target]) === true) {
            return $this->targets[$target];
        }
    }
}
