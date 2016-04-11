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

}