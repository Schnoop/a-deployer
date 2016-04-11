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

    /**
     * Compare locale with remote revision and return an array with files to upload/delete.
     *
     * @param string $localRevision
     *
     * @return array
     */
    public function compare($localRevision)
    {
        $remoteRevision = null;
        if ($this->filesystem->has($this->revisionFile) === true) {
            $remoteRevision = $this->filesystem->read($this->revisionFile);
        }
        $result = $this->git->diff($remoteRevision, $localRevision['sha1']);

        if ($remoteRevision === null) {
            return $result;
        }

        

        echo '<pre>' . print_r($result, 1) . '</pre>';
        die();
    }

}