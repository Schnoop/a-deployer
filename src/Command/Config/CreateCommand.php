<?php

namespace Antwerpes\ADeployer\Command\Config;

use CLIFramework\Command;
use Noodlehaus\Config;
use Noodlehaus\Exception\FileNotFoundException;

class CreateCommand extends Command
{

    public function brief()
    {
        return 'Create a-deployer.ini file with sample values';
    }

    function execute()
    {
        try {
            $config = new Config(getcwd() . DIRECTORY_SEPARATOR . 'a-deployer.ini');
        } catch (FileNotFoundException $e) {
            $result = file_put_contents(getcwd() . DIRECTORY_SEPARATOR . 'a-deployer.ini', '');
            if ($result === false) {
                $this->getLogger()->error('Configuration file has not been created. Error.');
                return;
            }

            $this->getLogger()->writeln($this->getFormatter()->format('Configuration file has been created.', 'strong_green'));
            return;
        }

        $this->getLogger()->error('Configuration file found. Aborting.');
    }
}