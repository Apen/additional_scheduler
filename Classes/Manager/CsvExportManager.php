<?php

namespace Sng\Additionalscheduler\Manager;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class CsvExportManager
 * @package Sng\Additionalscheduler\Manager
 */
class CsvExportManager extends QueryExportManager
{

    /**
     * @var string
     */
    protected $delimiter;
    /**
     * @var  string
     */
    protected $enclosure;
    /**
     * @var  string
     */
    protected $escape;
    /**
     * @var bool  string
     */
    protected $noHeader = false;

    /**
     * @param $noHeader bool
     * @return $this
     */
    public function setNoHeader($noHeader)
    {
        $this->noHeader = $noHeader;
        return $this;
    }

    /**
     * @param $delimiter string
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param $enclosure string
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * @param $escape string
     * @return $this
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;
        return $this;
    }

    /**
     * Create a temporary csv file and return its path
     * @param $filename
     * @return sring - the path to the csv file
     */
    public function renderFile($filename)
    {
        $temp = tempnam(sys_get_temp_dir(), $filename);
        $handle = fopen($temp, "w");
        $addHeader = !$this->noHeader;
        $this->parseResultSet(function ($row) use ($handle,$addHeader) {
            static $first = true;
            if ($first && $addHeader) {
                $cols = array_keys($row);
                fputcsv($handle, $cols, $this->delimiter, $this->enclosure, $this->escape);
                $first = false;
            }
            fputcsv($handle, $row, $this->delimiter, $this->enclosure, $this->escape);
        });
        fclose($handle);
        return $temp;
    }



}