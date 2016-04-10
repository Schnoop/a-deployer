<?php

namespace Antwerpes\ADeployer\Service;

/**
 * Class Git
 *
 * @package Antwerpes\ADeployer\Service
 */
class Git extends \SebastianBergmann\Git\Git
{

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

}