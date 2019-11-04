<?php

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class tx_additionalscheduler_savewebsite extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    public function execute()
    {
        require_once(PATH_site . 'typo3conf/ext/additional_scheduler/Classes/Utils.php');

        // exec SH
        $saveScript = PATH_site . 'typo3conf/ext/additional_scheduler/Resources/Shell/save_typo3_website.sh';
        $cmd = $saveScript . ' -p ' . PATH_site . ' -o ' . $this->path . ' -f';
        $return = shell_exec($cmd . ' 2>&1');

        // mail
        $mailTo = $this->email;
        $mailSubject = '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:task.savewebsite.name');
        $mailBody = $cmd . LF . LF . $return;

        if (empty($this->email) !== true) {
            \Sng\Additionalscheduler\Utils::sendEmail($mailTo, $mailSubject, $mailBody, 'plain', 'utf-8');
        }

        return true;
    }

    public function getAdditionalInformation()
    {
        return $this->path;
    }

}
