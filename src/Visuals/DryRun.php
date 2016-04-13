<?php

namespace Antwerpes\ADeployer\Visuals;

use Antwerpes\ADeployer\Model\Target;
use Antwerpes\ADeployer\Model\Transfer;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DryRun
 */
class DryRun
{

    /**
     * @param OutputInterface $output
     * @param Transfer        $transfer
     * @param Target          $target
     */
    public function render(OutputInterface $output, Transfer $transfer, Target $target)
    {
        //
        $tableRows = [];
        $tableCells = [];

        foreach ($transfer->getFilesToUpload() as $file) {
            $tableRows[] = [new TableCell($file)];
        }
        if (count($transfer->getFilesToDelete()) > 0) {
            $tableRows[] = new TableSeparator();
            $tableRows[] = [new TableCell('<error>Files that will be deleted.</error>')];
            $tableRows[] = new TableSeparator();
        }
        foreach ($transfer->getFilesToDelete() as $file) {
            $tableRows[] = [new TableCell($file)];
        }

        $tableCells[] = [new TableCell('SERVER: </info>' . $target->getName())];
        $tableCells[] = [new TableCell('<comment>Dry run: No remote files will be modified.</comment>')];
        if ($transfer->hasRemoteRevision() === false) {
            $tableCells[] = [new TableCell('<comment>No revision found - uploading everything...</comment>')];
        }

        $tableCells[] = [new TableCell('<info>Files that will be uploaded.</info>')];
        $tableCells[] = [new TableCell('File')];

        $table = new Table($output);
        $table->setHeaders($tableCells)->setRows($tableRows);
        $table->render();
        $output->writeln('');
    }

    /**
     * Convert $bytes in human readable size.
     *
     * @param int $bytes
     *
     * @return string
     */
    private function fileSizeConvert($bytes)
    {
        $result = 0;
        $bytes = floatval($bytes);
        $arBytes = [
            0 => [
                'UNIT' => 'TB',
                'VALUE' => pow(1024, 4),
            ],
            1 => [
                'UNIT' => 'GB',
                'VALUE' => pow(1024, 3),
            ],
            2 => [
                'UNIT' => 'MB',
                'VALUE' => pow(1024, 2),
            ],
            3 => [
                'UNIT' => 'KB',
                'VALUE' => 1024,
            ],
            4 => [
                'UNIT' => 'B',
                'VALUE' => 1,
            ],
        ];

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem['VALUE']) {
                $result = $bytes / $arItem['VALUE'];
                $result = str_replace('.', ',', strval(round($result, 2))) . ' ' . $arItem['UNIT'];
                break;
            }
        }

        return $result;
    }

}