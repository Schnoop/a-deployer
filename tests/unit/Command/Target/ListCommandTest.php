<?php


/**
 * Class ListCommandTest
 */
class ListCommandTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testConfigFileNotCreate()
    {
        $demoFile = getcwd() . DIRECTORY_SEPARATOR . 'phpunit-config.ini';
        if (file_exists($demoFile)) {
            unlink($demoFile);
        }
        file_put_contents($demoFile, "[foo]\n\r[bar]");

        $application = new \Symfony\Component\Console\Application();
        $commandInstance = new \Antwerpes\ADeployer\Command\Target\ListCommand();
        $commandInstance->setConfigFile('phpunit-config.ini');
        $application->add($commandInstance);

        $command = $application->find('targets');
        $commandTester = new \Symfony\Component\Console\Tester\CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertEquals("Valid deployment targets:

- foo
- bar

To start a deployment run \"(php) bin/a-deployer run <target>\"
",
            $commandTester->getDisplay());

        $demoFile = getcwd() . DIRECTORY_SEPARATOR . 'phpunit-config.ini';
        if (file_exists($demoFile)) {
            unlink($demoFile);
        }
    }
}