<?php

namespace Antwerpes\ADeployer\Traits;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Caommand
 *
 * @package Antwerpes\ADeployer\Traits
 */
trait Command
{

    protected $config = 'a-deployer.ini';

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
        $this->checkForConfigurationFile();
    }

    /**
     * Print application banner.
     *
     * @param OutputInterface $output
     */
    protected function printApplicationBanner(OutputInterface $output)
    {
        $style = new OutputFormatterStyle('white', 'magenta', ['bold']);
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</>');
        $output->writeln('<fire>!                                                     !</>');
        $output->writeln('<fire>!                   ' . $this->getApplication()->getName()
            . ' v' . $this->getApplication()->getVersion() . '                   !</>');
        $output->writeln('<fire>!                                                     !</>');
        $output->writeln('<fire>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</>');
        $output->writeln('');
    }

    /**
     * Check if configuration file exists and print message.
     */
    protected function checkForConfigurationFile()
    {
        if (file_exists(getcwd() . DIRECTORY_SEPARATOR . 'a-deployer.ini') === false) {
            throw new \Exception('Whoooops! ' . $this->getFullConfigPath() . ' does not exist.');
        }
    }

    /**
     * @return string
     */
    protected function getFullConfigPath()
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->config;
    }
}