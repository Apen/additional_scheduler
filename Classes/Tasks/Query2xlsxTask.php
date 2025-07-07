<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseEmailTask;
use Sng\Additionalscheduler\Manager\XlsxExportManager;
use Sng\Additionalscheduler\Utils;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Query2xlsxTask extends BaseEmailTask
{
    /**
     * @var string
     */
    public $query;

    /**
     * @var bool
     */
    public $noHeader;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var int
     */
    public $noDatetimeFlag;

    /**
     * @var string
     */
    public $body;

    /**
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function execute(): bool
    {
        $this->query = preg_replace('#\r\n#', ' ', $this->query);

        $mailSubject = $this->subject ?: $this->getDefaultSubject('query2xlsx');

        // Ensure PhpSpreadsheet is available
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            // Log error or notify admin, then return false or throw exception
            // For now, simple throw, but TYPO3 logging would be better
            throw new \RuntimeException('PhpSpreadsheet library is not available for Query2xlsxTask. Please install it via Composer.');
        }

        $path = GeneralUtility::makeInstance(XlsxExportManager::class)
            ->setQuery($this->query)
            ->setNoHeader((bool)$this->noHeader)
            ->renderFile($this->filename); // XlsxExportManager handles the .xlsx extension

        $finalFilename = $this->filename;
        // Remove .xlsx if present, as it might be added by user or by default
        if (str_ends_with(strtolower($finalFilename), '.xlsx')) {
            $finalFilename = substr($finalFilename, 0, -5);
        }


        if ($this->noDatetimeFlag == 0) {
            $finalFilename .= date('-Y-m-d_Hi');
        }

        $finalFilename .= '.xlsx'; // Ensure correct extension

        if (!empty($this->email)) {
            Utils::sendEmail($this->email, $mailSubject, $this->body, 'plain', 'utf-8', [$finalFilename => $path]);
        }

        unlink($path);
        return true;
    }
}
