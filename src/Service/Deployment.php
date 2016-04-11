<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;
use League\Flysystem\Filesystem;

/**
 * Class Deployment
 *
 * @package Antwerpes\ADeployer\Service
 */
class Deployment
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Compare constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function run(Transfer $transfer)
    {
        $filesToUpload = $transfer->getFilesToUpload();
        foreach ($transfer->getFilesToUpload() as $fileNo => $file) {
            $data = @file_get_contents($file);

            // It can happen the path is wrong, especially with included files.
            if ($data === false) {
                $this->cli->error(' ! File not found - please check path: ' . $file);
                continue;
            }

            $result = $this->filesystem->write($file, file_get_contents($file));

            if (!$result) {
                $this->cli->error(" ! Failed to upload {$file}.");
            } else {
                $this->deploymentSize += filesize($this->repo . '/' . ($this->currentSubmoduleName ? str_replace($this->currentSubmoduleName . '/',
                        '', $file) : $file));
            }

            $numberOfFilesToUpdate = count($filesToUpload);

            $fileNo = str_pad(++$fileNo, strlen($numberOfFilesToUpdate), ' ', STR_PAD_LEFT);
            $this->cli->lightGreen(" ^ $fileNo of $numberOfFilesToUpdate <white>{$file}");
        }
    }
}