<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;
use Exception;
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
     * @param Git $git
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
     * @return Transfer
     * @throws Exception
     */
    public function compare($localRevision)
    {
        $remoteRevision = null;
        $resultSet = new Transfer();
        if ($this->filesystem->has($this->revisionFile) === true) {
            $remoteRevision = $this->filesystem->read($this->revisionFile);
        }
        $result = $this->git->diff($remoteRevision, $localRevision);

        if ($remoteRevision === null) {
            $resultSet->setFilesToUpload($result);
            return $resultSet;
        }

        foreach ($result as $line) {
            if ($this->git->fileHasToBeUploaded($line[0])) {
                $resultSet->addFileToUpload(trim(substr($line, 1)));
            } elseif ($this->git->fileHasToBeDeleted($line[0])) {
                $resultSet->addFileToDelete(trim(substr($line, 1)));
            } else {
                throw new Exception("Unknown git-diff status.");
            }
        }
        return $resultSet;
    }

    public function storeRevision($revision)
    {
        $this->filesystem->put($this->revisionFile, $revision);
    }

}