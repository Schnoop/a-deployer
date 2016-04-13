<?php

namespace Antwerpes\ADeployer\Command\Config;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCommand.
 */
class CreateCommand extends AbstractCommand
{
    /**
     * @var string
     */
    public $data = '; NOTE: If non-alphanumeric characters in use, enclose value in quotes.

[staging]
; FTP
server[scheme] = ftp

; Set the hostname/IP for connection
server[host] = example.com

; Set the ftp port. Default for FTP: 21
server[port] = 21

; Username
server[username] = foo

; Password
server[password] = bar

; Use SSL or not. Default: false
server[ssl] = false

; Set the amount of seconds before the connection should timeout. Default: 90
server[timeout] = 90

; Set the root folder to work from.
server[root] = /

; Set the private permission value. Default: 0700
server[permPrivate] = 0700

; Set the public permission value. Default: 0744
server[permPublic] = 0744

; Set if passive mode should be used.  Default: true
server[passive] = true

; Set the transfer mode. Default: FTP_BINARY(2)
server[transferMode] = 2

; Set the FTP system type (windows or unix).
server[systemType] = unix

; If set to true, a visual notification will appear to tell the user that this deployment may be critical.
; This is maybe useful for live deployments
; It is NOT possible to bypass this via the --force flag. Default: false
critical = true

[production]
; SFTP
server[scheme] = sftp

; Set the hostname/IP for connection
server[host] = example.com

; Set the ftp port. Default for SFTP: 22
server[port] = 22

; Set the username to use for the connection
server[username] = foo

; Set the password to use for the connection
server[password] = bar

; Set the amount of seconds before the connection should timeout. Default: 90
server[timeout] = 90

; Set the root folder to work from.
server[root] = /

; Path or content of your private key
server[privateKey] = path/to/or/contents/of/privatekey

; Set the private permission value. Default: 0700
server[permPrivate] = 0700

; Set the public permission value.  Default: 0744
server[permPublic] = 0744

; Set permissions for new directory. Default: 0755
server[directoryPerm] = 0755

; If set to true, a visual notification will appear to tell the user that this deployment may be critical.
; This is maybe useful for live deployments
; It is NOT possible to bypass this via the --force flag. Default: false
critical = true

';

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
        // Empty. Only overritten to disabled check.
    }

    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Create a sample '.$this->getConfigFile().' file.');
    }

    /**
     * Execute command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (file_exists($this->getFullConfigPath()) === true) {
            $output->writeln('<error>'.$this->getConfigFile().' already found. Skipping.</error>');
            return;
        }
        if (file_put_contents($this->getFullConfigPath(), $this->data) === false) {
            // @codeCoverageIgnoreStart
            $output->writeln('<error>Sample '.$this->getConfigFile().' file has not been created.</error>');
            // @codeCoverageIgnoreEnd
        } // @codeCoverageIgnore
        $output->writeln('<info>Sample '.$this->getConfigFile().' file created.</info>');
    }
}
