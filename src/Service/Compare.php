<?php

namespace Antwerpes\ADeployer\Service;

use League\Flysystem\Filesystem;

/**
 * Class Compare
 *
 * @package Antwerpes\ADeployer\Service
 */
class Compare
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $revisionFile = '.revision';

    /**
     * @var Git
     */
    private $git;

    /**
     * Compare constructor.
     * @param Filesystem $filesystem
     * @param Git        $git
     */
    public function __construct(Filesystem $filesystem, Git $git)
    {
        $this->filesystem = $filesystem;
        $this->git = $git;
    }

    public function compare($localRevision)
    {
        $remoteRevision = null;
        if ($this->filesystem->has($this->revisionFile) === false) {
            // First deployment. We have to upload the whole project.
        } else {
            $remoteRevision = $this->filesystem->read($this->revisionFile);
        }

        $result = $this->git->getDiff($localRevision['sha1'], $remoteRevision);


        echo '<pre>' . print_r($result, 1) . '</pre>';
        die();
    }

}