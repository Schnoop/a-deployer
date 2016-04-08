<?php

namespace Antwerpes\ADeployer;

use CLIFramework\Application;
use GetOptionKit\OptionCollection;

/**
 * Class Console
 * @package Antwerpes\ADeployer
 */
class Console extends Application
{

    const NAME = 'A-Deployer';

    const VERSION = '1.0.0';

    public $showAppSignature = false;

    /**
     * @param OptionCollection $opts
     * @throws \GetOptionKit\Exception
     */
    public function options($opts)
    {
        $opts->add('v|verbose', 'verbose message');
        //$opts->add('path:', 'required option with a value.');
        //$opts->add('path?', 'optional option with a value');
        //$opts->add('path+', 'multiple value option.');
    }

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->command('targets', '\Antwerpes\ADeployer\Command\Target\ListCommand');
        $this->command('init', '\Antwerpes\ADeployer\Command\Config\CreateCommand');
        //$this->command('foo', '\Antwerpes\ADeployer\Command\FooCommand');
    }

}