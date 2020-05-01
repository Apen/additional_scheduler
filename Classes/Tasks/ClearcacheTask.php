<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClearcacheTask extends AbstractTask
{

    /**
     * Executes the commit task and returns TRUE if the execution was
     * succesfull
     *
     * @return bool
     */
    public function execute()
    {
        $GLOBALS['BE_USER']->user['admin'] = 1;
        $tce = GeneralUtility::makeInstance(DataHandler::class);
        $tce->start([], []);
        $tce->clear_cacheCmd('all');
        return true;
    }
}
