<?php

namespace Antwerpes\ADeployer\Bash;

use League\CLImate\CLImate;

/**
 * Class Cli
 *
 * @package Antwerpes\ADeployer\Bash
 */
class Cli
{

    /**
     * @var CLImate
     */
    protected $cliMate;

    /**
     * EyeCandy constructor.
     */
    public function __construct()
    {
        $this->cliMate = new CLImate();
    }

    /**
     * Print out application banner to bash.
     */
    public function printBanner()
    {
        $this->cliMate->backgroundGreen()->bold()->out('-------------------------------------------------------');
        $this->cliMate->backgroundGreen()->bold()->out('|                   A-Deployer v' . ADEPLOYER_VERSION . '                   |');
        $this->cliMate->backgroundGreen()->bold()->out('-------------------------------------------------------');
    }

    /**
     * Returns instance of CLIMate
     *
     * @return CLImate
     */
    public function getCli()
    {
        return $this->cliMate;
    }
}