<?php

namespace Antwerpes\ADeployer\Command\Deployment;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Antwerpes\ADeployer\Model\Target;
use Antwerpes\ADeployer\Model\Transfer;
use Antwerpes\ADeployer\Service\Compare;
use Antwerpes\ADeployer\Service\Connection;
use Antwerpes\ADeployer\Service\Deployment;
use Antwerpes\ADeployer\Service\Filter;
use Antwerpes\ADeployer\Service\Includes;
use Antwerpes\ADeployer\Traits\Command as CommandTrait;
use Exception;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class RunCommand
 *
 * @package Antwerpes\ADeployer\Command\Deployment;
 */
class RunCommand extends AbstractCommand
{

    use CommandTrait;

    /**
     * @var Target
     */
    protected $targetConfig;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Print application banner
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws Exception
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        // Check for target existance.
        $target = $input->getArgument('target');
        if ($this->getConfig()->isAvailableTarget($target) === false) {
            throw new Exception($target . ' is not a known deployment target.');
        }

        // Show basic information about deployment.
        $this->printDeploymentBanner($input, $output);

        // Show alert message.
        $this->targetConfig = $this->getConfig()->getConfigForTarget($target);
        if ($this->targetConfig->isCritialDeployment() === true) {
            $this->printCriticalBanner($output);
        }
    }

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
            )->addOption('dry-run', null, InputOption::VALUE_NONE, 'Print what would happen.')
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
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        // Dry run. Nothing will happen.
        if ($input->getOption('dry-run') === true) {
            $output->writeln('<comment>Dry run: No remote files will be modified.</comment>');
        }

        // No password found in ini file.
        if ($this->targetConfig->hasPassword() === false) {
            $this->targetConfig->setPassword($this->getPassword());
        }

        $service = new Connection();
        $connection = $service->getConnection($this->targetConfig);

        $compare = new Compare($connection, $this->getGitInstance());
        $resultSet = $compare->compare($this->getGitInstance()->getLatestRevisionHash());

        $filter = new Filter($this->targetConfig->getExcludes());
        $resultSet = $filter->filter($resultSet);

        if ($input->getOption('no-includes') === false) {
            $includes = new Includes($this->targetConfig->getIncludes());
            $resultSet = $includes->add($resultSet);
        }

        // Dry run. Print out and leave.
        if ($input->getOption('dry-run') === true) {
            $this->printDryRun($resultSet);
            return;
        }

        $deployment = new Deployment($connection);
        $deployment->run($resultSet);

        echo '<pre>' . print_r($resultSet, 1) . '</pre>';
        die();

        die();
        echo 'Go!';

        // Last call for action!
        if ($this->reallyProceedWithDeployment() === false) {
            return;
        }

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
        $helper = $this->getHelper('question');
        if (($this->input->getOption('force') === false
                || $this->targetConfig->isCritialDeployment() === true)
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