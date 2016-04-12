<?php

namespace Antwerpes\ADeployer\Command\Target;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Antwerpes\ADeployer\Traits\Command as CommandTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * @package Antwerpes\ADeployer\Command\Support;
 */
class ListCommand extends AbstractCommand
{

    use CommandTrait;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('targets')
            ->setDescription('List all available deployment targets.');
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
        $config = $this->getConfig();

        $output->writeln('<info>Valid deployment targets:</info>');
        $output->writeln('');
        foreach ($config->getAvailableTargets() as $target) {
            $output->writeln('<comment>- ' . $target . '</comment>');
        }
        $output->writeln('');
        $output->writeln('<info>To start a deployment run "(php) bin/a-deployer run <target>"</info>');
    }
}