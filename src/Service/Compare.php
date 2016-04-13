<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Compare.
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
     * @var OutputInterface
     */
    private $output;

    /**
     * Compare constructor.
     *
     * @param Filesystem      $filesystem
     * @param Git             $git
     * @param OutputInterface $output
     */
    public function __construct(Filesystem $filesystem, Git $git, OutputInterface $output)
    {
        $this->filesystem = $filesystem;
        $this->git = $git;
        $this->output = $output;
    }

    /**
     * Compare locale with remote revision and return an array with files to upload/delete.
     *
     * @param string $localRevision
     *
     * @throws RuntimeException
     *
     * @return Transfer
     */
    public function compare($localRevision)
    {
        $remoteRevision = null;
        $resultSet = new Transfer();
        if ($this->filesystem->has($this->revisionFile) === true) {
            $remoteRevision = $this->filesystem->read($this->revisionFile);
            $resultSet->setRemoteRevision($remoteRevision);
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
                throw new RuntimeException('Unknown git-diff status.');
            }
        }

        return $resultSet;
    }

    /**
     * Store $revision is revision file on server.
     *
     * @param string $revision
     */
    public function storeRevision($revision)
    {
        $this->filesystem->put($this->revisionFile, $revision);
    }
}
