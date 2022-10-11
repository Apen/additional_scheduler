<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Scheduler\Task\AbstractTask;

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
     */
    protected function getDefaultSubject(string $task): string
    {
        return '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . $task . '.name');
    }
}
