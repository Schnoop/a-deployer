<?php

namespace Antwerpes\ADeployer\Traits;

use Antwerpes\ADeployer\Service\Config;
use Antwerpes\ADeployer\Service\Git;
use SebastianBergmann\Git\RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command.
 */
trait Command
{
    /**
     * Config file.
     *
     * @var string
     */
    public $config = 'a-deployer.ini';

    /**
     * Config file.
     *
     * @var string
     */
    protected $password = '.a-deployer';

    /**
     * Git directory.
     *
     * @var string
     */
    protected $git = '.git';

    /**
     * Print application banner.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->getConfig();
        $this->getGitInstance();
    }

    /**
     * Check if configuration file exists and print message.
     *
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     *
     * @return Config
     */
    protected function getConfig()
    {
        return new Config($this->openIniFile($this->getFullConfigPath()));
    }

    /**
     * Open ini file, parse and return as an array.
     *
     * @param string $file
     *
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     *
     * @return array
     */
    protected function openIniFile($file)
    {
        if (file_exists($file) === false) {
            throw new \Symfony\Component\Console\Exception\RuntimeException('Whoooops! '.$file.' does not exist.');
        }
        $values = parse_ini_file($file, true);
        if ($values === false) {
            throw new \Symfony\Component\Console\Exception\RuntimeException($file.' is not a valid .ini file.');
        }

        return $values;
    }

    /**
     * Returns full path to config file.
     *
     * @return string
     */
    protected function getFullConfigPath()
    {
        return getcwd().DIRECTORY_SEPARATOR.$this->config;
    }

    /**
     * Check for valid git repository.
     *
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     *
     * @return Git
     */
    protected function getGitInstance()
    {
        try {
            $repository = new Git($this->getGitDirectory());
        } catch (RuntimeException $e) {
            throw new \Symfony\Component\Console\Exception\RuntimeException(
                'Whoooops!'.$this->getGitDirectory().' is not a valid git repository . ');
        }

        return $repository;
    }

    /**
     * Returns full path to git repository.
     *
     * @return string
     */
    protected function getGitDirectory()
    {
        return getcwd().DIRECTORY_SEPARATOR;
    }

    /**
     * Print red banner to tell the user that he has to be careful.
     *
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function printCriticalBanner(OutputInterface $output)
    {
        $style = new OutputFormatterStyle('white', 'red', ['bold']);
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>-----------------------------------------------</>');
        $output->writeln('<fire>!  BE CAREFUL: THIS IS A CRITICAL DEPLOYMENT  !</>');
        $output->writeln('<fire>-----------------------------------------------</>');
        $output->writeln('');
    }

    /**
     * Print info banner about deployment.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function printDeploymentBanner(InputInterface $input, OutputInterface $output)
    {
        $target = $input->getArgument('target');
        $revision = $this->getGitInstance()->getLatestRevision();
        $branch = $this->getGitInstance()->getCurrentBranch();
        $output->writeln('<info>Will deployment revision </info><comment>"'.$revision['sha1'].'"</comment><info> from </info><comment>"'.
            $branch.'"</comment><info> branch to target </info><comment>"'.$target.'"</comment>');
        $output->writeln('<info>Revision created from </info><comment>"'.$revision['author'].'"</comment>');
        $output->writeln('<info>Revision created at </info><comment>"'.$revision['date']->format('d.m.Y H:i:s').'"</comment>');
        $output->writeln('<info>Revision message </info><comment>"'.$revision['message'].'"</comment>');
        $output->writeln('');
    }

    /**
     * Returns full path to password file.
     *
     * @return string
     */
    protected function getFullPasswordFilePath()
    {
        return getcwd().DIRECTORY_SEPARATOR.$this->password;
    }
}
