<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler\Manager;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
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
     * @param bool $noHeader
     * @return $this
     */
    public function setNoHeader(bool $noHeader)
    {
        $this->noHeader = $noHeader;
        return $this;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function setDelimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param string $enclosure
     * @return $this
     */
    public function setEnclosure(string $enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * @param string $escape
     * @return $this
     */
    public function setEscape(string $escape)
    {
        $this->escape = $escape;
        return $this;
    }

    /**
     * Create a temporary csv file and return its path
     *
     * @param string $filename
     * @return string - the path to the csv file
     */
    public function renderFile(string $filename): string
    {
        $temp = tempnam(sys_get_temp_dir(), $filename);
        $handle = fopen($temp, 'w');
        fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        $addHeader = !$this->noHeader;
        $this->parseResultSet(function ($row) use ($handle, $addHeader): void {
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
