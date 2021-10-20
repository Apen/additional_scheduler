<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseEmailTask;
use Sng\Additionalscheduler\Utils;

class SavewebsiteTask extends BaseEmailTask
{
    public $savedir;
    /**
     * @var string
     */
    public $path;

    public function execute()
    {
        // exec SH
        $saveScript = Utils::getPathSite() . 'typo3conf/ext/additional_scheduler/Resources/Shell/save_typo3_website.sh';
        if (!is_executable($saveScript)) {
            throw new \ErrorException($saveScript.' must be executable');
        }

        $cmd = $saveScript . ' -p ' . Utils::getPathSite() . ' -o ' . $this->savedir . ' -f';

        $return = shell_exec($cmd . ' 2>&1');

        // mail
        $mailTo = $this->email;
        $mailBody = $cmd . LF . LF . $return;
        $mailSubject = $this->subject ?: $this->getDefaultSubject('savewebsite');

        if (!empty($this->email)) {
            Utils::sendEmail($mailTo, $mailSubject, $mailBody, 'plain', 'utf-8');
        }

        return true;
    }

    public function getAdditionalInformation()
    {
        return $this->savedir;
    }
}
