<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;

/**
 * Class Excludes.
 */
class Excludes
{
    /**
     * Default set to ignore.
     *
     * @var array
     */
    private $exclude = ['.git*'];

    /**
     * Filter constructor.
     *
     * @param array $exclude
     */
    public function __construct(array $exclude)
    {
        $this->exclude = array_merge($this->exclude, $exclude);
    }

    /**
     * Filter ignore files.
     *
     * @param Transfer $transfer
     *
     * @return Transfer
     */
    public function filter(Transfer $transfer)
    {
        $filesToDelete = $transfer->getFilesToDelete();
        $transfer->setFilesToDelete($this->useFilter($filesToDelete, $transfer));

        $filesToUpload = $transfer->getFilesToUpload();
        $transfer->setFilesToUpload($this->useFilter($filesToUpload, $transfer));

        return $transfer;
    }

    /**
     * Filter given $files.
     *
     * @param array    $files
     * @param Transfer $transfer
     *
     * @return array
     */
    private function useFilter($files, Transfer $transfer)
    {
        foreach ($files as $i => $file) {
            foreach ($this->exclude as $pattern) {
                if ($this->patternMatch($pattern, $file) === 1) {
                    unset($files[$i]);
                    $transfer->addFileToSkip($file);
                    break;
                }
            }
        }

        return $files;
    }

    /**
     * Return true if $pattern matches $string.
     *
     * @param string $pattern
     * @param string $string
     *
     * @return bool
     */
    protected function patternMatch($pattern, $string)
    {
        return preg_match('#^'.strtr(preg_quote($pattern, '#'), ['\*' => '.*', '\?' => '.']).'$#i', $string);
    }
}
