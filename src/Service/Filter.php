<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;

/**
 * Class Filter
 *
 * @package Antwerpes\ADeployer\Service
 */
class Filter
{
    /**
     * @var array
     */
    private $exclude;

    /**
     * Filter constructor.
     * @param array $exclude
     */
    public function __construct(array $exclude)
    {
        $this->exclude = $exclude;
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
        foreach ($filesToDelete as $i => $file) {
            foreach ($this->exclude as $pattern) {
                if ($this->patternMatch($pattern, $file) === true) {
                    unset($filesToDelete[$i]);
                    $transfer->addFileToSkip($file);
                    break;
                }
            }
        }
        $transfer->setFilesToDelete($filesToDelete);

        $filesToUpload = $transfer->getFilesToUpload();
        foreach ($filesToUpload as $i => $file) {
            foreach ($this->exclude as $pattern) {
                if ($this->patternMatch($pattern, $file) === 1) {
                    unset($filesToUpload[$i]);
                    $transfer->addFileToSkip($file);
                    break;
                }
            }
        }
        $transfer->setFilesToUpload($filesToUpload);

        return $transfer;
    }

    /**
     * Return true if $pattern matches $string
     *
     * @param string $pattern
     * @param string $string
     *
     * @return boolean
     */
    protected function patternMatch($pattern, $string)
    {
        return preg_match('#^' . strtr(preg_quote($pattern, '#'), ['\*' => '.*', '\?' => '.']) . '$#i', $string);
    }
}