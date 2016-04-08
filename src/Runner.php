<?php

namespace Antwerpes\ADeployer;

use Antwerpes\ADeployer\Bash\Cli;
use Antwerpes\ADeployer\Command\Help;
use Antwerpes\ADeployer\Command\Init;


/**
 * Class Runner
 *
 * @package Antwerpes\ADeployer
 */
class Runner
{

    const INI_FILE = 'a-deployer.ini';

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
     */
    public function __construct()
    {
        $this->cli = new Cli();
        $this->configuration = new Configuration();
    }

    /**
     * Run A-Deloyer
     */
    public function run()
    {
        $this->cli->printBanner();

        $command = new Init($this->cli, $this->configuration);
        $command->run();
    }

}