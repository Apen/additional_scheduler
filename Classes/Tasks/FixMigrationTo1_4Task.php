<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseEmailTask;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Sng\Additionalscheduler\Templating;
use Sng\Additionalscheduler\Utils;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class FixMigrationTo1_4Task extends AbstractTask
{
    public function execute()
    {

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
            $query = sprintf($queryTpl, $this->getSerializedName($oldName)
                , addslashes($this->getSerializedName($namespace.$newName)));
           $queryBuilder->getConnection()->executeQuery($query);
        }

        return true;
    }

    protected function getSerializedName($str)
    {
        return 'O:'.strlen($str).':"'.$str.'"';
    }
}
