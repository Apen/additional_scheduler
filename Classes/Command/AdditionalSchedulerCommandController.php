<?php

namespace Sng\Additionalscheduler\Command;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Class AdditionalSchedulerCommandController
 * @package Sng\Additionalscheduler\Command
 */
class AdditionalSchedulerCommandController extends CommandController
{

    /**
     * Rename old classes in tx_scheduler_task to fit with the new ones in version 1.4
     * @return string
     */
    public function fixUpdateTo1_4Command()
    {

        $log = [];
        // exec query
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('additional_scheduler');
        $namespace = 'Sng\\Additionalscheduler\\Tasks\\';
        $queryTpl = "update tx_scheduler_task set serialized_task_object = replace(serialized_task_object, '%s','%s')";
        foreach ([
                     'tx_additionalscheduler_execquery' => 'ExecqueryTask',
                     'tx_additionalscheduler_exec' => 'ExecTask',
                     'tx_additionalscheduler_clearcache' => 'ClearcacheTask',
                     'tx_additionalscheduler_cleart3temp' => 'Cleart3tempTask',
                     'tx_additionalscheduler_savewebsite' => 'SavewebsiteTask',
                     'tx_additionalscheduler_query2csv' => 'Query2csvTask',
                 ] as $oldName => $newName) {
            $log[] = 'Renaming '.$oldName. ' to '.$newName;
            $log[] = "----------------------------------------------------------------------------\n";
            $query = sprintf($queryTpl, $this->getSerializedName($oldName)
                , addslashes($this->getSerializedName($namespace.$newName)));
            $log[] = 'SQL query : ' ;
            $log[] = $query ;
            $log[] = "\n";
            $stmt = $queryBuilder->getConnection()->executeQuery($query);
            $log[] = $stmt->rowCount(). " rows updated\n";
        }
        return implode("\n", $log);

    }

    /**
     * @param $str
     * @return string
     */
    protected function getSerializedName($str)
    {
        return 'O:'.strlen($str).':"'.$str.'"';
    }

}