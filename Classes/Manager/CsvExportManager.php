<?php


namespace Sng\Additionalscheduler\Manager;


use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CsvExportManager extends QueryExportManager
{

    protected $delimiter;
    protected $enclosure;
    protected $escape;
    protected $noHeader = false;

    /**
     * @param mixed $noHeader
     */
    public function setNoHeader($noHeader)
    {
        $this->noHeader = $noHeader;
        return $this;
    }

    /**
     * @param mixed $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param mixed $enclosure
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * @param mixed $escape
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;
        return $this;
    }



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