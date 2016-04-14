<?php

/**
 * Class IncludesTest.
 */
class IncludesTest extends PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $baseFolder = 'demo-folder-' . date('his', time());
        $folders = [
            'tests/' . $baseFolder .'/demo2/demo2.1',
            'tests/' . $baseFolder .'/demo2',
            'tests/' . $baseFolder .'/demo3',
            'tests/' . $baseFolder .'/demo4',
            'tests/' . $baseFolder .'/demo1/demo1.1/demo1.1.1',
            'tests/' . $baseFolder .'/demo1/demo1.1',
            'tests/' . $baseFolder .'/demo1',
        ];

        foreach ($folders as $folder) {
            @rmdir($folder);
        }
        @rmdir('tests/' . $baseFolder .'/');

        foreach ($folders as $folder) {
            @mkdir($folder, 0777, true);
        }

        $foldersProof = [
            'tests/' . $baseFolder .'/demo2/demo2.1',
            'tests/' . $baseFolder .'/demo2',
            'tests/' . $baseFolder .'/demo3',
            'tests/' . $baseFolder .'/demo4',
            'tests/' . $baseFolder .'/demo1/demo1.1/demo1.1.1',
            'tests/' . $baseFolder .'/demo1/demo1.1',
            'tests/' . $baseFolder .'/demo1',
        ];

        $transfer = new \Antwerpes\ADeployer\Model\Transfer();
        $includes = new \Antwerpes\ADeployer\Service\Includes();
        $transfer = $includes->add($transfer, ['tests/' . $baseFolder]);
        $this->assertEquals($foldersProof, $transfer->getFilesToUpload());
    }
}
