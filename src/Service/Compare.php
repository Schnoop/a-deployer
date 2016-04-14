<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * Class Compare.
 */
class Compare
{
    /**
     * @var string
     */
    protected $revisionFile = '.revision';

    /**
     * Compare locale with remote revision and return an array with files to upload/delete.
     *
     * @param string     $localRevision
     * @param Filesystem $filesystem
     * @param Git        $git
     * @param Transfer   $resultSet
     *
     * @return Transfer
     */
    public function compare($localRevision, Filesystem $filesystem, Git $git, Transfer $resultSet)
    {
        $remoteRevision = null;
        if ($filesystem->has($this->revisionFile) === true) {
            $remoteRevision = $filesystem->read($this->revisionFile);
            $resultSet->setRemoteRevision($remoteRevision);
        }
        $result = $git->diff($remoteRevision, $localRevision);

        if ($remoteRevision === null) {
            $resultSet->setFilesToUpload($result);

            return $resultSet;
        }

        foreach ($result as $line) {
            if ($git->fileHasToBeUploaded($line[0])) {
                $resultSet->addFileToUpload(trim(substr($line, 1)));
            } elseif ($git->fileHasToBeDeleted($line[0])) {
                $resultSet->addFileToDelete(trim(substr($line, 1)));
            } else {
                throw new RuntimeException('Unknown git-diff status.');
            }
        }

        return $resultSet;
    }

    /**
     * Returns name of revision file.
     *
     * @return string
     */
    public function getRevisionFile()
    {
        return $this->revisionFile;
    }
}
