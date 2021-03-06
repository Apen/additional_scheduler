<?php

namespace Sng\Additionalscheduler;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class BaseEmailTask
 */
abstract class BaseEmailTask extends AbstractTask
{

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $subject;

    /**
     * Default subject for emails
     *
     * @param string $task
     * @return string
     */
    protected function getDefaultSubject($task): string
    {
        return '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . $task . '.name');
    }
}
