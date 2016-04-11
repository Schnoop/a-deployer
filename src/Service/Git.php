<?php

namespace Antwerpes\ADeployer\Service;

/**
 * Class Git
 *
 * @package Antwerpes\ADeployer\Service
 */
class Git extends \SebastianBergmann\Git\Git
{

    const FILE_CREATED = 'A';

    const FILE_COPIED = 'C';

    const FILE_DELETED = 'D';

    const FILE_MODIFIED = 'M';

    const FILE_TYPE_CHANGED = 'T';

    /**
     * @var array
     */
    public $fileHasToBeUploaded = array(
        self::FILE_CREATED,
        self::FILE_COPIED,
        self::FILE_MODIFIED,
        self::FILE_TYPE_CHANGED
    );

    /**
     * Returns sha1 hash from latest revision
     *
     * @return string
     * @throws \Exception
     */
    public function getLatestRevisionHash()
    {
        $revision = $this->getLatestRevision();
        return $revision['sha1'];
    }

    /**
     * Returns latest revision
     *
     * @return array
     * @throws \Exception
     */
    public function getLatestRevision()
    {
        $revisions = $this->getRevisions();
        if (count($revisions) === 0) {
            throw new \Exception('No commits found.');
        }
        return end($revisions);
    }

    /**
     * Make diff between to git revisions and return output
     *
     * @param string $remoteRevision
     * @param string $localRevision
     *
     * @return array
     */
    public function diff($remoteRevision, $localRevision)
    {
        if (empty($remoteRevision)) {
            $command = 'ls-files';
        } elseif ($localRevision === 'HEAD') {
            $command = 'diff --name-status ' . $remoteRevision . ' ' . $localRevision;
        } else {
            // What's the point of this ELSE clause?
            $command = 'diff --name-status ' . $remoteRevision . ' ' . $localRevision;
        }

        return $this->execute($command);
    }

    /**
     * Returns true if $status is in array that means you have to upload a file.
     *
     * @param string $status
     *
     * @return boolean
     */
    public function fileHasToBeUploaded($status)
    {
        return in_array($status, $this->fileHasToBeUploaded);
    }

    /**
     * Returns true if $status means you have to delete the file
     *
     * @param string $status
     *
     * @return boolean
     */
    public function fileHasToBeDeleted($status)
    {
        return $status === self::FILE_DELETED;
    }
}