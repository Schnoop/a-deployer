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
        foreach ($transfer->getFilesToUpload() as $file) {
            $result = $this->filesystem->write($file, file_get_contents($file));
        }
    }
}