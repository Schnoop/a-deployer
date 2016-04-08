<?php

namespace Antwerpes\ADeployer\Command;

/**
 * Class Init
 *
 * @package Antwerpes\ADeployer\Command
 */
class Init extends AbstractCommand
{

    /**
     * Run command.
     */
    public function run()
    {
        $data = "; NOTE: If non-alphanumeric characters are present, enclose in value in quotes.\n
[staging]
    quickmode = ftp://example:password@production-example.com:21/path/to/installation\n
[staging]
    scheme = sftp
    user = example
    pass = password
    host = staging-example.com
    path = /path/to/installation
    port = 22";

        $iniFile = $this->configuration->get('ini_file');
        $path = getcwd() . DIRECTORY_SEPARATOR . $iniFile;

        if (file_exists($path) === true) {
            $this->cli->getCli()->comment("\n" . $iniFile . " file already exists. Aborting.\n");
            return;
        }

        if (file_put_contents($path, $data)) {
            $this->cli->getCli()->info("\nSample " . $iniFile . " file created.\n");
            return;
        }
        $this->cli->getCli()->error("\nUnable to create sample " . $iniFile . " file.\n");
    }

}