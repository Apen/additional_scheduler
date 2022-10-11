<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseEmailTask;
use Sng\Additionalscheduler\Utils;

class ExecTask extends BaseEmailTask
{
    /**
     * @var string
     */
    public $execdir;

    /**
     * Executes the commit task and returns TRUE if the execution was
     * succesfull
     *
     * @return bool
     */
    public function execute(): bool
    {
        $cmd = substr($this->execdir, 0, 1) === '/' ? $this->execdir : Utils::getPathSite() . $this->execdir;

        $return = shell_exec($cmd . ' 2>&1');

        // mail
        $mailTo = $this->email;
        $mailSubject = $this->subject ?: $this->getDefaultSubject('exec');
        $mailBody = $cmd . LF . LF . $return;

        if (!empty($this->email)) {
            Utils::sendEmail($mailTo, $mailSubject, $mailBody, 'plain', 'utf-8');
        }

        return true;
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return string
     */
    public function getAdditionalInformation(): string
    {
        return $this->execdir;
    }
}
