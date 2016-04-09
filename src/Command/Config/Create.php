<?php

namespace Antwerpes\ADeployer\Command\Config;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GreetCommand
 *
 * @package Antwerpes\ADeployer\Command
 */
class Create extends AbstractCommand
{

    protected $data = "; NOTE: If non-alphanumeric characters are present, enclose in value in quotes.\n
[staging]
quickmode = ftp://example:password@production-example.com:21/path/to/installation\n
[staging]
scheme = sftp
user = example
pass = password
host = staging-example.com
path = /path/to/installation
port = 22";

    /**
     * Print application banner
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->printApplicationBanner($output);
    }


    /**
     *
     */
    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Create a sample a-deployer.ini file');
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        file_put_contents($this->getFullConfigPath(), '');
        $output->writeln('<info>Sample ' . $this->config . ' file created.</info>');
    }
}