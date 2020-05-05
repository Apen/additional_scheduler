<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseEmailTask;
use Sng\Additionalscheduler\Manager\CsvExportManager;
use Sng\Additionalscheduler\Utils;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Query2csvTask
 */
class Query2csvTask extends BaseEmailTask
{

    /**
     * @var string
     */
    public $query;

    /**
     * @var string
     */
    public $delimiter;

    /**
     * @var string
     */
    public $enclosure;

    /**
     * @var string
     */
    public $escape;

    /**
     * @var int
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
     */
    public function execute()
    {
        $this->query = preg_replace('#\r\n#', ' ', $this->query);

        $mailSubject = $this->subject ?: $this->getDefaultSubject('query2csv');
        $path = GeneralUtility::makeInstance(CsvExportManager::class)
            ->setQuery($this->query)
            ->setDelimiter($this->delimiter)
            ->setEnclosure($this->enclosure)
            ->setEscape($this->escape)
            ->setNoHeader($this->noHeader)
            ->renderFile($this->filename);
        $filename = str_replace('.csv', '', $this->filename);
        if ($this->noDatetimeFlag === 0) {
            $filename .= date('-Y-m-d_Hi');
        }
        $filename .= '.csv';
        if (!empty($this->email)) {
            Utils::sendEmail($this->email, $mailSubject, $this->body, 'plain', 'utf-8', [$filename => $path]);
        }
        unlink($path);
        return true;
    }
}
