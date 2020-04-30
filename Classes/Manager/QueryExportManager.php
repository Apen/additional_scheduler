<?php


namespace Sng\Additionalscheduler\Manager;


use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Driver\PDOSqlsrv\Statement;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class QueryExportManager
{
    protected $query;

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    public function parseResultSet(\Closure $func)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('additional_scheduler');
        $stmt = $queryBuilder->getConnection()->executeQuery($this->query);
        while ($row = $stmt->fetch()) {
            $func($row);
        }
    }
}