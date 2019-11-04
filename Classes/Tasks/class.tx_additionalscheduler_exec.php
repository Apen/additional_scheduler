<?php

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class tx_additionalscheduler_exec extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    /**
     * Executes the commit task and returns TRUE if the execution was
     * succesfull
     *
     * @return    boolean    returns TRUE on success, FALSE on failure
     */
    public function execute()
    {
        // exec SH
        if (substr($this->path, 0, 1) == '/') {
            $cmd = $this->path;
        } else {
            $cmd = PATH_site . $this->path;
        }

        $return = shell_exec($cmd . ' 2>&1');

        // mail
        $mailTo = $this->email;
        $mailSubject = '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:task.exec.name');
        $mailBody = $cmd . LF . LF . $return;

        if (empty($this->email) !== true) {
            \Sng\Additionalscheduler\Utils::sendEmail($mailTo, $mailSubject, $mailBody, 'plain', 'utf-8');
        }

        return true;
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return    string    Information to display
     */
    public function getAdditionalInformation()
    {
        return $this->path;
    }

}
