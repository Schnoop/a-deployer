<?php

namespace Antwerpes\ADeployer\Service;

use Symfony\Component\Console\Exception\RuntimeException;

/**
 * Class Git.
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
    public $fileHasToBeUploaded = [
        self::FILE_CREATED,
        self::FILE_COPIED,
        self::FILE_MODIFIED,
        self::FILE_TYPE_CHANGED,
    ];

    /**
     * Returns sha1 hash from latest revision.
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getLatestRevisionHash()
    {
        $revision = $this->getLatestRevision();

        return $revision['sha1'];
    }

    /**
     * Returns latest revision.
     *
     * @throws RuntimeException
     *
     * @return array
     */
    public function getLatestRevision()
    {
        $revisions = $this->getRevisions();
        if (count($revisions) === 0) {
            throw new RuntimeException('No commits found.');
        }

        return end($revisions);
    }

    /**
     * Make diff between to git revisions and return output.
     *
     * @param string      $localRevision
     * @param string|null $remoteRevision
     *
     * @return array
     */
    public function diff($localRevision, $remoteRevision = null)
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
     * @return bool
     */
    public function fileHasToBeUploaded($status)
    {
        return in_array($status, $this->fileHasToBeUploaded);
    }

    /**
     * Returns true if $status means you have to delete the file.
     *
     * @param string $status
     *
     * @return bool
     */
    public function fileHasToBeDeleted($status)
    {
        return $status === self::FILE_DELETED;
    }
}
