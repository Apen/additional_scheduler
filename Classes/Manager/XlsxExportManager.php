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
     * @param string $filename The desired filename (e.g., "my_export.xlsx")
     * @return string - the path to the xlsx file
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function renderFile(string $filename): string
    {
        if (!class_exists(Spreadsheet::class)) {
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

        // Ensure the input filename has a base name for uniqid
        $baseFilename = basename($filename, '.xlsx');
        if (empty(trim($baseFilename))) {
            $baseFilename = 'export'; // Default base if original was empty or just ".xlsx"
        }
        // Further sanitize baseFilename to remove characters that might cause issues
        $sanitizedBaseFilename = preg_replace('/[^a-zA-Z0-9_-]/', '', $baseFilename);
        if (empty($sanitizedBaseFilename)) {
            $sanitizedBaseFilename = 'export'; // Fallback if sanitization results in empty string
        }

        // Ensure filename for temp file ends with .xlsx, and is unique
        $tempFilename = uniqid($sanitizedBaseFilename . '_', true) . '.xlsx';
        $tempFilePath = GeneralUtility::getFileAbsFileName($tempDir . '/' . $tempFilename);

        if (empty($tempFilePath)) {
            // This case should ideally not be reached if $tempDir and $tempFilename are valid
            throw new \RuntimeException('Could not generate a valid temporary file path.');
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        return $tempFilePath;
    }
}
