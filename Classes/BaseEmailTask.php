<?php


namespace Sng\Additionalscheduler;


use  TYPO3\CMS\Scheduler\Task\AbstractTask;

abstract class BaseEmailTask extends  AbstractTask
{

    protected function getDefaultSubject($task)
    {
        return '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.'.$task.'.name');
    }
}