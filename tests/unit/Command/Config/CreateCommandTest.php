<?php


/**
 * Class TestCreateCommand
 */
class CreateCommandTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testConfigFileCreate()
    {
        $demoFile = getcwd() . DIRECTORY_SEPARATOR . 'phpunit-config.ini';
        if (file_exists($demoFile)) {
            unlink($demoFile);
        }

        $application = new \Symfony\Component\Console\Application();
        $commandInstance = new \Antwerpes\ADeployer\Command\Config\CreateCommand();
        $commandInstance->configFile = 'phpunit-config.ini';
        $application->add($commandInstance);

        $command = $application->find('init');
        $commandTester = new \Symfony\Component\Console\Tester\CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/Sample ' . $commandInstance->configFile . ' file created./', $commandTester->getDisplay());

        $this->assertTrue(file_exists($demoFile));

        $this->assertEquals($commandInstance->data, file_get_contents($demoFile));

        $demoFile = getcwd() . DIRECTORY_SEPARATOR . 'phpunit-config.ini';
        if (file_exists($demoFile)) {
            unlink($demoFile);
        }
    }

    /**
     *
     */
    public function testConfigFileNotCreate()
    {
        $demoFile = getcwd() . DIRECTORY_SEPARATOR . 'phpunit-config.ini';
        if (file_exists($demoFile)) {
            unlink($demoFile);
        }
        file_put_contents($demoFile, 'DemoContent');

        $application = new \Symfony\Component\Console\Application();
        $commandInstance = new \Antwerpes\ADeployer\Command\Config\CreateCommand();
        $commandInstance->config = 'phpunit-config.ini';
        $application->add($commandInstance);

        $command = $application->find('init');
        $commandTester = new \Symfony\Component\Console\Tester\CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/' . $commandInstance->configFile . ' already found. Skipping./',
            $commandTester->getDisplay());

        $demoFile = getcwd() . DIRECTORY_SEPARATOR . 'phpunit-config.ini';
        if (file_exists($demoFile)) {
            unlink($demoFile);
        }
    }
}