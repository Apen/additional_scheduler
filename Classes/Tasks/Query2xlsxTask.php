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
    public $filename; // This should be populated by TYPO3 scheduler from saved configuration

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
            throw new \RuntimeException('PhpSpreadsheet library is not available for Query2xlsxTask. Please install it via Composer.');
        }

        // Fallback for filename if it's empty
        $currentFilename = trim($this->filename ?? '');
        if (empty($currentFilename)) {
            $currentFilename = 'data.xlsx'; // Default filename
        }

        $path = GeneralUtility::makeInstance(XlsxExportManager::class)
            ->setQuery($this->query)
            ->setNoHeader((bool)$this->noHeader)
            ->renderFile($currentFilename); // Use the potentially defaulted filename

        // Prepare the final filename for the email attachment
        $finalAttachmentFilename = $currentFilename;
        // Remove .xlsx if present, as it might be added by user or by default, for timestamp logic
        if (strtolower(substr($finalAttachmentFilename, -5)) === '.xlsx') {
            $finalAttachmentFilename = substr($finalAttachmentFilename, 0, -5);
        }

        if ($this->noDatetimeFlag == 0) {
            $finalAttachmentFilename .= date('-Y-m-d_Hi');
        }

        $finalAttachmentFilename .= '.xlsx'; // Ensure correct extension

        if (!empty($this->email)) {
            Utils::sendEmail($this->email, $mailSubject, $this->body, 'plain', 'utf-8', [$finalAttachmentFilename => $path]);
        }

        unlink($path);
        return true;
    }
}
