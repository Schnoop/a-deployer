<?php

namespace Antwerpes\ADeployer\Model;

/**
 * Class Transfer.
 */
class Transfer
{
    /**
     * Array with files to delete.
     *
     * @var array
     */
    protected $delete = [];

    /**
     * Array with files to upload.
     *
     * @var array
     */
    protected $upload = [];

    /**
     * Array with files to skip while uploading and deleting.
     *
     * @var array
     */
    protected $skip = [];

    /**
     * @var string
     */
    protected $remoteRevision = null;

    /**
     * Add file to upload array.
     *
     * @param string $file
     */
    public function addFileToUpload($file)
    {
        $this->upload[] = $file;
    }

    /**
     * Add file to delete array.
     *
     * @param string $file
     */
    public function addFileToDelete($file)
    {
        $this->delete[] = $file;
    }

    /**
     * Set files to upload.
     *
     * @param array $files
     */
    public function setFilesToUpload(array $files)
    {
        $this->upload = $files;
    }

    /**
     * Set files to delete.
     *
     * @param array $files
     */
    public function setFilesToDelete(array $files)
    {
        $this->delete = $files;
    }

    /**
     * Returns array with files to delete.
     *
     * @return array
     */
    public function getFilesToDelete()
    {
        return $this->delete;
    }

    /**
     * Returns array with files to upload.
     *
     * @return array
     */
    public function getFilesToUpload()
    {
        return $this->upload;
    }

    /**
     * Set files to skip.
     *
     * @param array $files
     */
    public function setFilesToSkip(array $files)
    {
        $this->skip = $files;
    }

    /**
     * Add $file to skip array.
     *
     * @param string $file
     */
    public function addFileToSkip($file)
    {
        $this->skip[] = $file;
    }

    /**
     * Returns array with files to skip.
     *
     * @return array
     */
    public function getFilesToSkip()
    {
        return $this->skip;
    }

    /**
     * Add files to upload.
     *
     * @param array $files
     */
    public function addFilesToUpload(array $files)
    {
        $this->upload = array_merge($this->upload, $files);
    }

    /**
     * @return bool
     */
    public function hasRemoteRevision()
    {
        return $this->remoteRevision !== null;
    }

    /**
     * @return string
     */
    public function getRemoteRevision()
    {
        return $this->remoteRevision;
    }

    /**
     * @param string $remoteRevision
     */
    public function setRemoteRevision($remoteRevision)
    {
        $this->remoteRevision = $remoteRevision;
    }
}
