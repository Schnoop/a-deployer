<?php

namespace Antwerpes\ADeployer\Command\Deployment;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Antwerpes\ADeployer\Model\Target;
use Antwerpes\ADeployer\Model\Transfer;
use Antwerpes\ADeployer\Service\Compare;
use Antwerpes\ADeployer\Service\Connection;
use Antwerpes\ADeployer\Service\Deployment;
use Antwerpes\ADeployer\Service\Excludes;
use Antwerpes\ADeployer\Service\Includes;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class RunCommand.
 */
class RunCommand extends AbstractCommand
{
    /**
     * @var Target
     */
    protected $targetConfig;

    /**
     * Print application banner.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws RuntimeException
     *
     * @return void
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Run deployment')
            ->addArgument('target', InputArgument::OPTIONAL, 'Where to deploy the code')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Print what would happen.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Do not ask for confirmation.')
            ->addOption('no-includes', null, InputOption::VALUE_NONE,
                'Skip all includes, although configured in config file.');
    }

    /**
     * Execute command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws RuntimeException
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get target from input.
        $target = $this->input->getArgument('target');

        // If a target has been chosen.
        if (strlen($target) > 0) {
            if ($this->getConfig()->isAvailableTarget($target) === false) {
                throw new RuntimeException('"' . $target . '" is not a valid target. Please check available targets with "(php) bin/a-deployer targets"');
            }
            $this->deploy($this->getConfig()->getConfigForTarget($target));
            return;
        }

        // Deploy to all known server.
        foreach ($this->getConfig()->getAvailableTargets() as $target) {
            $this->deploy($this->getConfig()->getConfigForTarget($target));
        }
    }

    /**
     * Run deployment
     *
     * @param Target $target
     *
     * @return void
     */
    protected function deploy(Target $target)
    {
        $this->output->writeln('');
        $this->output->writeln('<info>SERVER:</info> ' . $target->getName());

        // No password found in ini file.
        if ($target->hasPassword() === false) {
            $target->setPassword($this->getPassword());
        }

        $service = new Connection();
        $filesystem = $service->getConnection($target);

        $compare = new Compare($filesystem, $this->getGitInstance());
        $resultSet = $compare->compare('HEAD');

        $excludes = new Excludes($target->getExcludes());
        $resultSet = $excludes->filter($resultSet);

        // Use includes if option is not false
        if ($this->input->getOption('no-includes') === false) {
            $includes = new Includes($target->getIncludes());
            $resultSet = $includes->add($resultSet);
        }

        // Dry run. Print out and leave.
        if ($this->input->getOption('dry-run') === true) {
            $this->printDryRun($resultSet);

            return;
        }

        // Last call for action!
        if ($this->reallyProceedWithDeployment() === false) {
            return;
        }

        $deployment = new Deployment($filesystem, $this->output);
        $deployment->run($resultSet);

        $compare->storeRevision($this->getGitInstance()->getLatestRevisionHash());

        die();
    }

    /**
     * Get password from any other way that ini file.
     *
     * @return string
     */
    protected function getPassword()
    {
        // Check for password file.
        if (file_exists($this->getFullPasswordFilePath()) === true) {
            $config = $this->openIniFile($this->getFullPasswordFilePath());
            if (isset($config[$this->targetConfig->getName()]['password'])) {
                return $config[$this->targetConfig->getName()]['password'];
            }
        }

        // Ask user via console.
        $helper = $this->getHelper('question');
        $question = new Question('<info>No password has been provided for user "'
            . $this->targetConfig['server']['username'] . '". Please enter a password: </info>');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $password = $helper->ask($this->input, $this->output, $question);

        if (strlen($password) === 0) {
            $this->output->writeln('<comment>You entered an empty password. Continuing deployment anyway ...</comment>');

            return $password;
        }
        $this->output->writeln('<comment>Password received. Continuing deployment ...</comment>');

        return $password;
    }

    /**
     * Print out what will happen.
     *
     * @param Transfer $transfer
     *
     * @return void
     */
    protected function printDryRun(Transfer $transfer)
    {
        $this->printBlock(['Dry run!', 'No remote files will be modified.']);

        if (count($transfer->getFilesToUpload()) === 0 && count($transfer->getFilesToDelete()) === 0) {
            $this->output->writeln('<info>   No files to upload.</info>');

            return;
        }

        if (count($transfer->getFilesToDelete()) > 0) {
            $this->output->writeln('<error>   Files that will be deleted in next deployment:</error>');
            foreach ($transfer->getFilesToDelete() as $file_to_delete) {
                $this->output->writeln('      ' . $file_to_delete);
            }
        }

        if (count($transfer->getFilesToUpload()) > 0) {
            $this->output->writeln('<info>   Files that will be uploaded in next deployment:</info>');
            foreach ($transfer->getFilesToUpload() as $file_to_upload) {
                $this->output->writeln('      ' . $file_to_upload);
            }
        }
    }

    /**
     * Ask the user if really proceed with deployment.
     *
     * @return bool
     */
    protected function reallyProceedWithDeployment()
    {
        // Show alert message.
        if ($this->targetConfig->isCriticalDeployment() === true) {
            $this->printCriticalBanner($this->output);
        }

        $helper = $this->getHelper('question');
        if (($this->input->getOption('force') === false
                || $this->targetConfig->isCriticalDeployment() === true)
            && $this->input->getOption('dry-run') === false
        ) {
            $question = new ConfirmationQuestion(
                '<comment>Continue with this action? (y|j|yes|ja): </comment>',
                false,
                '/^(y|j|yes|ja)/i'
            );

            return $helper->ask($this->input, $this->output, $question);
        }

        return true;
    }
}
