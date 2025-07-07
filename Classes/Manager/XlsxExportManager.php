<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler\Manager;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class XlsxExportManager extends QueryExportManager
{
    /**
     * @var bool
     */
    protected bool $noHeader = false;

    /**
     * @param bool $noHeader
     * @return $this
     */
    public function setNoHeader(bool $noHeader): self
    {
        $this->noHeader = $noHeader;
        return $this;
    }

    /**
     * Create a temporary xlsx file and return its path
     *
     * @param string $filename
     * @return string - the path to the xlsx file
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function renderFile(string $filename): string
    {
        if (!class_exists(Spreadsheet::class)) {
            // This check is basic. A more robust check or handling might be needed
            // depending on how dependencies are managed in the TYPO3 extension (e.g., Composer autoloading).
            // For now, we assume if this class is called, the library should be available.
            // A proper dependency check will be part of a later step.
            throw new \RuntimeException('PhpSpreadsheet library is not available. Please install it via Composer: composer require phpoffice/phpspreadsheet');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNumber = 1;
        $addHeader = !$this->noHeader;

        $this->parseResultSet(function ($row) use ($sheet, $addHeader, &$rowNumber): void {
            static $first = true;
            if ($first && $addHeader) {
                $col = 'A';
                foreach (array_keys($row) as $headerCell) {
                    $sheet->setCellValue($col . $rowNumber, $headerCell);
                    $col++;
                }
                $rowNumber++;
                $first = false;
            }

            $col = 'A';
            foreach ($row as $dataCell) {
                $sheet->setCellValue($col . $rowNumber, $dataCell);
                $col++;
            }
            $rowNumber++;
        });

        $tempDir = sys_get_temp_dir();
        // Ensure filename ends with .xlsx
        if (!str_ends_with($filename, '.xlsx')) {
            $filename .= '.xlsx';
        }
        $tempFilePath = GeneralUtility::getFileAbsFileName($tempDir . '/' . uniqid(basename($filename, '.xlsx') . '_', true) . '.xlsx');

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        return $tempFilePath;
    }
}
