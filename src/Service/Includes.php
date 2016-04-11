<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;

/**
 * Class Includes
 *
 * @package Antwerpes\ADeployer\Service
 */
class Includes
{
    /**
     * @var array
     */
    private $includes;

    /**
     * Filter constructor.
     * @param array $includes
     */
    public function __construct(array $includes)
    {
        $this->includes = $includes;
    }

    /**
     * Filter ignore files.
     *
     * @param Transfer $transfer
     *
     * @return Transfer
     */
    public function add(Transfer $transfer)
    {
        $transfer->addFilesToUpload($this->parseFolder());
        return $transfer;
    }

    /**
     * @return array
     */
    private function parseFolder()
    {
        $filteredFiles = [];
        foreach ($this->includes as $i => $file) {
            $name = getcwd() . '/' . $file;
            if (is_dir($name)) {
                $filteredFiles = array_merge($filteredFiles,
                    array_map([$this, 'relPath'], $this->directoryToArray($name, true)));
            } else {
                $filteredFiles[] = $file;
            }
        }

        return $filteredFiles;
    }

    /**
     * Get an array that represents directory tree
     * Credit: http://php.net/manual/en/function.scandir.php#109140.
     *
     * @param string $directory Directory path
     * @param bool   $recursive Include sub directories
     * @param bool   $listDirs Include directories on listing
     * @param bool   $listFiles Include files on listing
     * @param string $exclude Exclude paths that matches this regex
     *
     * @return array
     */
    public function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '')
    {
        $arrayItems = array();
        $skipByExclude = false;
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
                if ($exclude) {
                    preg_match($exclude, $file, $skipByExclude);
                }
                if (!$skip && !$skipByExclude) {
                    if (is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
                        if ($recursive) {
                            $arrayItems = array_merge($arrayItems,
                                $this->directoryToArray($directory . DIRECTORY_SEPARATOR . $file, $recursive, $listDirs,
                                    $listFiles, $exclude));
                        }
                        if ($listDirs) {
                            $file = $directory . DIRECTORY_SEPARATOR . $file;
                            $arrayItems[] = $file;
                        }
                    } else {
                        if ($listFiles) {
                            $file = $directory . DIRECTORY_SEPARATOR . $file;
                            $arrayItems[] = $file;
                        }
                    }
                }
            }
            closedir($handle);
        }

        return $arrayItems;
    }

    /**
     * Strip Absolute Path.
     *
     * @param string $el
     *
     * @return string
     */
    protected function relPath($el)
    {
        $abs = getcwd() . '/';

        return str_replace($abs, '', $el);
    }
}