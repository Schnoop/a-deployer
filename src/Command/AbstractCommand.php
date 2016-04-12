<?php

namespace Antwerpes\ADeployer\Command;

use Antwerpes\ADeployer\Traits\Command as CommandTrait;
use Symfony\Component\Console\Command\Command;

/**
 * Class GreetCommand.
 */
abstract class AbstractCommand extends Command
{
    use CommandTrait;
}
