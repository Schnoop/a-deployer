<?php

namespace Antwerpes\ADeployer\Command\Target;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Antwerpes\ADeployer\Traits\Command as CommandTrait;

/**
 * Class ListCommand
 *
 * @package Antwerpes\ADeployer\Command\Support;
 */
class ListCommand extends AbstractCommand
{

    use CommandTrait;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('targets')
            ->setDescription('List all available deployment targets.');
    }
}