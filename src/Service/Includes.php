<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;

/**
 * Class Includes.
 */
class Includes
{
    /**
     * Filter ignore files.
     *
     * @param Transfer $transfer
     * @param array    $includes
     *
     * @return Transfer
     */
    public function add(Transfer $transfer, array $includes)
    {
        $transfer->addFilesToUpload($this->parseFolder($includes));

        return $transfer;
    }

    /**
     * Parse folder.
     *
     * @param array $includes
     *
     * @return array
     */
    private function parseFolder($includes)
    {
        $filteredFiles = [];
        foreach ($includes as $i => $file) {
            $name = getcwd().DIRECTORY_SEPARATOR.$file;
            if (is_dir($name)) {
                $filteredFiles = array_merge($filteredFiles,
                    array_map([$this, 'getRelativePath'], $this->directoryToArray($name, true)));
            } else {
                $filteredFiles[] = $file;
            }
        }

        return $filteredFiles;
    }

    /**
     * Get an array that represents directory tree.
     *
     * @param string $directory Directory path
     * @param bool   $recursive Include sub directories
     *
     * @return array
     */
    protected function directoryToArray($directory, $recursive = true)
    {
        $arrayItems = [];
        $handle = opendir($directory);
        while (false !== ($file = readdir($handle))) {
            preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
            if (!$skip) {
                if (is_dir($directory.DIRECTORY_SEPARATOR.$file)) {
                    if ($recursive) {
                        $arrayItems = array_merge($arrayItems,
                            $this->directoryToArray($directory.DIRECTORY_SEPARATOR.$file, $recursive));
                    }
                    $file = $directory.DIRECTORY_SEPARATOR.$file;
                    $arrayItems[] = $file;
                } else {
                    $file = $directory.DIRECTORY_SEPARATOR.$file;
                    $arrayItems[] = $file;
                }
            }
        }
        closedir($handle);

        return $arrayItems;
    }

    /**
     * Remove absolute path from $el.
     *
     * @param string $el
     *
     * @return string
     */
    protected function getRelativePath($el)
    {
        return str_replace(getcwd().DIRECTORY_SEPARATOR, '', $el);
    }
}
