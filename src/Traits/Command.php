<?php

namespace Antwerpes\ADeployer\Traits;

use Exception;
use SebastianBergmann\Git\Git;
use SebastianBergmann\Git\RuntimeException;
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

    /**
     * Config file.
     *
     * @var string
     */
    protected $config = 'a-deployer.ini';

    /**
     * Git directory.
     *
     * @var string
     */
    protected $git = '.git';

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
        $this->getConfig();
        $this->getGitInstance();
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
     *
     * @return array
     * @throws Exception
     */
    protected function getConfig()
    {
        if (file_exists($this->getFullConfigPath()) === false) {
            throw new Exception('Whoooops! ' . $this->getFullConfigPath() . ' does not exist.');
        }
        $values = parse_ini_file($this->getFullConfigPath(), true);
        if ($values === false) {
            throw new \Exception($this->getFullConfigPath() . ' is not a valid .ini file.');
        } else {
            return $values;
        }
    }

    /**
     * Check for valid git repository
     *
     * @return Git
     * @throws Exception
     */
    protected function getGitInstance()
    {
        try {
            $repository = new Git($this->getGitDirectory());
        } catch (RuntimeException $e) {
            throw new Exception('Whoooops!' . $this->getGitDirectory() . ' is not a valid git repository . ');
        }
        return $repository;
    }

    /**
     * Returns full path to config file
     *
     * @return string
     */
    protected function getFullConfigPath()
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->config;
    }

    /**
     * Returns full path to git repository
     *
     * @return string
     */
    protected function getGitDirectory()
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->git;
    }
}