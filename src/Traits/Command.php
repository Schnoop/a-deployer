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
    public $configFile = 'a-deployer.ini';

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
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

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

        $this->input = $input;
        $this->output = $output;

        $style = new OutputFormatterStyle('black', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('notification', $style);
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
            throw new \Symfony\Component\Console\Exception\RuntimeException('Whoooops! ' . $file . ' does not exist.');
        }
        $values = @parse_ini_file($file, true);
        if ($values === false) {
            throw new \Symfony\Component\Console\Exception\RuntimeException($file . ' is not a valid .ini file.');
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
        return getcwd() . DIRECTORY_SEPARATOR . $this->configFile;
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
                'Whoooops!' . $this->getGitDirectory() . ' is not a valid git repository . ');
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
        return getcwd() . DIRECTORY_SEPARATOR;
    }

    /**
     * Returns name of config file.
     *
     * @return string
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * Set name of config file.
     *
     * @param string $config
     */
    public function setConfigFile($config)
    {
        $this->configFile = $config;
    }

    /**
     * Print $message as block
     *
     * @param array           $message
     * @param string          $style
     * @param bool            $padded
     *
     * @return void
     */
    protected function printBlock($message, $style = 'notification', $padded = true)
    {
        $formatter = $this->getHelper('formatter');
        $formattedBlock = $formatter->formatBlock($message, $style, $padded);
        $this->output->writeln('');
        $this->output->writeln($formattedBlock);
        $this->output->writeln('');
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
     * Returns full path to password file.
     *
     * @return string
     */
    protected function getFullPasswordFilePath()
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->password;
    }
}
