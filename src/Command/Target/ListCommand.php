<?php

namespace Antwerpes\ADeployer\Command\Target;

use CLIFramework\Command;
use Noodlehaus\Config;
use Noodlehaus\Exception\FileNotFoundException;

/**
 * Class ListCommand
 *
 * @package Antwerpes\ADeployer\Command\Target
 */
class ListCommand extends Command
{

    /**
     * @return string
     */
    public function brief()
    {
        return 'List all known targets for deployment.';
    }

    /**
     *
     */
    function execute()
    {
        try {
            $config = new Config(getcwd() . DIRECTORY_SEPARATOR . 'a-deployer.ini');
        } catch (FileNotFoundException $e) {
            $this->getLogger()->error('Configuration file not found. Run "(php) a-deployer init" in the terminal to create the a-deployer.ini file or create one manually.');
            return;
        }

        $this->getLogger()->println($this->getFormatter()->format('Supported target(s)', 'strong_green'));

        $this->getLogger()->newline();
        foreach ($config->all() as $target => $values) {
            $this->getLogger()->println($this->getFormatter()->format('  -' . $target, 'yellow'));
            $this->getLogger()->newline();
        }

        $this->getLogger()->println($this->getFormatter()->format('To start a deployment run "(php) a-deployer deploy --target <targetname>"', 'strong_green'));
    }
}