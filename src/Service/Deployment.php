<?php

namespace Antwerpes\ADeployer\Service;

use Antwerpes\ADeployer\Model\Transfer;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Deployment.
 */
class Deployment
{

    /**
     * Run.
     *
     * @param Transfer        $transfer
     * @param Filesystem      $filesystem
     * @param OutputInterface $output
     */
    public function run(Filesystem $filesystem, OutputInterface $output, Transfer $transfer)
    {
        $filesToUpload = $transfer->getFilesToUpload();
        $numberOfFilesToUpdate = count($filesToUpload);
        foreach ($transfer->getFilesToUpload() as $fileNo => $file) {
            $data = @file_get_contents($file);

            // It can happen the path is wrong, especially with included files.
            if ($data === false) {
                $output->writeln('<error> ! File not found - please check path: ' . $file . '</error>');
                continue;
            }

            $result = $filesystem->put($file, $data);

            if ($result === false) {
                $output->writeln('<error> ! Failed to upload ' . $file . '.</error>');
            }

            $fileNo = str_pad(++$fileNo, strlen($numberOfFilesToUpdate), ' ', STR_PAD_LEFT);
            $output->writeln(" ^ $fileNo of $numberOfFilesToUpdate {$file}");
        }

        $filesToDelete = $transfer->getFilesToDelete();
        $numberOfFilesToDelete = count($filesToDelete);
        foreach ($transfer->getFilesToDelete() as $fileNo => $file) {
            $fileNo = str_pad(++$fileNo, strlen($numberOfFilesToDelete), ' ', STR_PAD_LEFT);
            if ($filesystem->has($file) === false) {
                $output->writeln("<error> ! $fileNo of $numberOfFilesToDelete </error> {$file} not found");
                continue;
            }
            $filesystem->delete($file);
            $output->writeln("<error> × $fileNo of $numberOfFilesToDelete</error> {$file}");
        }

        $dirsToDelete = $this->hasDeletedDirectories($filesToDelete);
        $numberOfDirsToDelete = count($dirsToDelete);
        foreach ($dirsToDelete as $dirNo => $dir) {
            $dirNo = str_pad(++$dirNo, strlen($numberOfDirsToDelete), ' ', STR_PAD_LEFT);
            if ($filesystem->has($dir) === false) {
                $output->writeln("<error> ! $dirNo of $numberOfDirsToDelete</error> {$dir} not found");
                continue;
            }
            $filesystem->deleteDir($dir);
            $output->writeln("<error> × $dirNo of $numberOfDirsToDelete</error> {$dir}");
        }
    }

    /**
     * Checks for deleted directories. Git cares only about files.
     *
     * @param array $filesToDelete
     *
     * @return array
     */
    public function hasDeletedDirectories($filesToDelete)
    {
        $dirsToDelete = [];
        foreach ($filesToDelete as $file) {

            // Break directories into a list of items
            $parts = explode('/', $file);
            // Remove files name from the list
            array_pop($parts);

            foreach ($parts as $i => $part) {
                $prefix = '';
                // Add the parent directories to directory name
                for ($x = 0; $x < $i; ++$x) {
                    $prefix .= $parts[$x] . '/';
                }

                $part = $prefix . $part;

                // If directory doesn't exist, add to files to delete
                // Relative path won't work consistently, thus getcwd().
                if (!is_dir(getcwd() . '/' . $part)) {
                    $dirsToDelete[] = $part;
                }
            }
        }

        // Remove duplicates
        $dirsToDeleteUnique = array_unique($dirsToDelete);

        // Reverse order to delete inner children before parents
        $dirsToDeleteOrder = array_reverse($dirsToDeleteUnique);

        return $dirsToDeleteOrder;
    }
}
