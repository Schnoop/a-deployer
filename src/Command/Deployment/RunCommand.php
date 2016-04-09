<?php

namespace Antwerpes\ADeployer\Command\Deployment;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Antwerpes\ADeployer\Traits\Command as CommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 *
 * @package Antwerpes\ADeployer\Command\Deployment;
 */
class RunCommand extends AbstractCommand
{

    use CommandTrait;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Run deployment')
            ->addArgument(
                'target',
                InputArgument::REQUIRED,
                'Where to deploy the code'
            )->addOption('dry-run', null, null, 'Print what would happen.');
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

        $output->writeln('<info>Known deployment targets:</info>');
        $output->writeln('');
        foreach (array_keys($config) as $target) {
            $output->writeln('<comment>- ' . $target . '</comment>');
        }
        $output->writeln('');
        $output->writeln('<info>To start a deployment run "(php) bin/a-deployer run --target <target>"</info>');
    }
}