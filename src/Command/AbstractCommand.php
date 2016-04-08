<?php

namespace Antwerpes\ADeployer\Command;

use Antwerpes\ADeployer\Bash\Cli;
use Antwerpes\ADeployer\Configuration;

/**
 * Class AbstractCommand
 *
 * @package Antwerpes\ADeployer\Command
 */
abstract class AbstractCommand
{

    /**
     * @var Cli
     */
    protected $cli;
    
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * Runner constructor.
     *
     * @param Cli           $cli
     * @param Configuration $configuration
     */
    public function __construct(Cli $cli, Configuration $configuration)
    {
        $this->cli = $cli;
        $this->configuration = $configuration;
    }

}