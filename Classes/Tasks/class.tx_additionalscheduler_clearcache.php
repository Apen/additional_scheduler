<?php

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class tx_additionalscheduler_clearcache extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    /**
     * Executes the commit task and returns TRUE if the execution was
     * succesfull
     *
     * @return    bool    returns TRUE on success, FALSE on failure
     */
    public function execute()
    {
        $GLOBALS['BE_USER']->user['admin'] = 1;
        $tce = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
        $tce->start([], []);
        $tce->clear_cacheCmd('all');
        return true;
    }
}
