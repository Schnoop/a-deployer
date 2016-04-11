<?php

namespace Antwerpes\ADeployer\Command\Deployment;

use Antwerpes\ADeployer\Command\AbstractCommand;
use Antwerpes\ADeployer\Model\Target;
use Antwerpes\ADeployer\Service\Compare;
use Antwerpes\ADeployer\Service\Connection;
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
            ->addOption('force', null, InputOption::VALUE_NONE, 'Do not ask for confirmation.');
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

        // Last call for action!
        if ($this->reallyProceedWithDeployment() === false) {
            return;
        }

        $service = new Connection();
        $connection = $service->getConnection($this->targetConfig);

        $compare = new Compare($connection, $this->getGitInstance());
        $compare->compare($this->getGitInstance()->getLatestRevision());

        echo '<pre>' . print_r($connection, 1) . '</pre>';
        die();
        echo 'Go!';

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