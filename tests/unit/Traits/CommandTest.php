<?php


/**
 * Class CommandTraitStub.
 */
class CommandTraitStub
{
    use \Antwerpes\ADeployer\Traits\Command;
}

/**
 * Class IncludesTest.
 */
class CommandTest extends PHPUnit_Framework_TestCase
{
    public function testConfigFile()
    {
        $instance = new CommandTraitStub();
        $instance->setConfigFile('fooBar.ini');

        $this->assertEquals('fooBar.ini', $instance->getConfigFile());
    }

    public function testInitializeWithoutConfigFile()
    {
        $instance = new CommandTraitStub();
        $inputMock = \Mockery::mock('Symfony\Component\Console\Input\InputInterface');
        $outputMock = \Mockery::mock('Symfony\Component\Console\Output\OutputInterface');

        $file = 'fooBar.ini';
        $instance->setConfigFile($file);

        @unlink(getcwd().DIRECTORY_SEPARATOR.$file);

        $this->expectException('\Symfony\Component\Console\Exception\RuntimeException');
        $this->expectExceptionMessage('Whoooops! '.getcwd().DIRECTORY_SEPARATOR.$file.' does not exist.');

        $instance->initialize($inputMock, $outputMock);
    }

    public function testInitializeWithoutValidConfigFile()
    {
        $instance = new CommandTraitStub();
        $inputMock = \Mockery::mock('Symfony\Component\Console\Input\InputInterface');
        $outputMock = \Mockery::mock('Symfony\Component\Console\Output\OutputInterface');

        $file = 'fooBar.ini';
        $instance->setConfigFile($file);

        file_put_contents(getcwd().DIRECTORY_SEPARATOR.$file, '%&');

        $this->expectException('\Symfony\Component\Console\Exception\RuntimeException');
        $this->expectExceptionMessage(getcwd().DIRECTORY_SEPARATOR.$file.' is not a valid .ini file.');

        $instance->initialize($inputMock, $outputMock);
    }
}
