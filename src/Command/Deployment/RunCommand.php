<?php

namespace Antwerpes\ADeployer\Command\Deployment;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Antwerpes\ADeployer\Traits\Command as CommandTrait;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

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
            )->addOption('dry-run', null, null, 'Print what would happen.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Do not ask for confirmation.');
    }

    /**
     * Print application banner
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Exception
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $target = $input->getArgument('target');
        if ($this->getConfig()->isAvailableTarget($target) === false) {
            throw new \Exception($target . ' is not a known deployment target.');
        }

        $revision = $this->getGitInstance()->getLatestRevision();
        $branch = $this->getGitInstance()->getCurrentBranch();

        $output->writeln('<info>Will deployment revision </info><comment>"' . $revision['sha1'] . '"</comment><info> from </info><comment>"' .
            $branch . '"</comment><info> branch to target </info><comment>"' . $target . '"</comment>');
        $output->writeln('<info>Revision created from </info><comment>"' . $revision['author'] . '"</comment>');
        $output->writeln('<info>Revision created at </info><comment>"' . $revision['date']->format('d.m.Y H:i:s') . '"</comment>');
        $output->writeln('<info>Revision message </info><comment>"' . $revision['message'] . '"</comment>');

        if ($this->getConfig()->isCritialDeployment($target) === true) {
            $style = new OutputFormatterStyle('white', 'red', ['bold']);
            $output->getFormatter()->setStyle('fire', $style);
            $output->writeln('<fire>BE CAREFUL: THIS IS A CRITICAL DEPLOYMENT</>');
        }
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $target = $input->getArgument('target');
        if ($input->getOption('force') === false || $this->getConfig()->isCritialDeployment($target) === true) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion(
                'Continue with this action? (y|j|yes|ja): ',
                false,
                '/^(y|j|yes|ja)/i'
            );
            if ($helper->ask($input, $output, $question) === false) {
                return;
            }
        }
        echo 'Go!';

    }
}