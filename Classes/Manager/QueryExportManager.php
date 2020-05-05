<?php

namespace Sng\Additionalscheduler\Manager;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Closure;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class QueryExportManager
 */
class QueryExportManager
{
    /**
     * The sql query
     * @var string
     */
    protected $query;

    /**
     * @param string $query
     * @return QueryExportManager
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Execute the query and parse the resultSet by executing the bypassed callback
     * @param Closure $func - function that accept a unique array parameter
     */
    public function parseResultSet(Closure $func)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('additional_scheduler');
        $stmt = $queryBuilder->getConnection()->executeQuery($this->query);
        while ($row = $stmt->fetch()) {
            $func($row);
        }
    }
}
