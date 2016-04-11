<?php

namespace Antwerpes\ADeployer\Model;

/**
 * Class Transfer
 *
 * @package Antwerpes\ADeployer\Model
 */
class Transfer
{
    /**
     * Array with files to delete
     *
     * @var array
     */
    protected $delete = array();

    /**
     * Array with files to upload
     *
     * @var array
     */
    protected $upload = array();

    /**
     * Array with files to skip while uploading and deleting
     *
     * @var array
     */
    protected $skip = array();

    /**
     * Add file to upload array
     *
     * @param string $file
     */
    public function addFileToUpload($file)
    {
        $this->upload[] = $file;
    }

    /**
     * Add file to delete array
     *
     * @param string $file
     */
    public function addFileToDelete($file)
    {
        $this->delete[] = $file;
    }

    /**
     * Set files to upload
     *
     * @param array $files
     */
    public function setFilesToUpload(array $files)
    {
        $this->upload = $files;
    }

    /**
     * Set files to delete
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
     * Set files to skip
     *
     * @param array $files
     */
    public function setFilesToSkip(array $files)
    {
        $this->skip = $files;
    }

    /**
     * Add $file to skip array
     *
     * @param string $file
     */
    public function addFileToSkip($file)
    {
        $this->skip[] = $file;
    }

    /**
     * Add files to upload
     *
     * @param array $files
     */
    public function addFilesToUpload(array $files)
    {
        $this->upload = array_merge($this->upload, $files);
    }
}