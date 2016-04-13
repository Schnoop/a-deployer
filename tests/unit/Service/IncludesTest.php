<?php

/**
 * Class IncludesTest.
 */
class IncludesTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testFilter()
    {
        $folders = [
            'tests/demo-folder/demo2/demo2.1',
            'tests/demo-folder/demo2',
            'tests/demo-folder/demo3',
            'tests/demo-folder/demo4',
            'tests/demo-folder/demo1/demo1.1/demo1.1.1',
            'tests/demo-folder/demo1/demo1.1',
            'tests/demo-folder/demo1',
            'tests/demo-folder/.git',
            'tests/demo-folder/.svn',
        ];

        foreach ($folders as $folder) {
            @rmdir($folder);
        }
        @rmdir('tests/demo-folder');

        foreach ($folders as $folder) {
            @mkdir($folder, 0777, true);
        }

        $foldersProof = [
            'tests/demo-folder/demo2/demo2.1',
            'tests/demo-folder/demo2',
            'tests/demo-folder/demo3',
            'tests/demo-folder/demo4',
            'tests/demo-folder/demo1/demo1.1/demo1.1.1',
            'tests/demo-folder/demo1/demo1.1',
            'tests/demo-folder/demo1',
        ];

        $transfer = new \Antwerpes\ADeployer\Model\Transfer();
        $includes = new \Antwerpes\ADeployer\Service\Includes(['tests/demo-folder']);
        $transfer = $includes->add($transfer);
        $this->assertEquals($foldersProof, $transfer->getFilesToUpload());
    }

}