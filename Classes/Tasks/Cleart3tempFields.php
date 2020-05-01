<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class Cleart3tempFields extends AdditionalFieldProviderInterface
{
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {
        if (empty($taskInfo['additionalscheduler_nbdays'])) {
            $taskInfo['additionalscheduler_nbdays'] = $parentObject->CMD == 'edit' ? $task->nbdays : '';
        }
        if (empty($taskInfo['additionalscheduler_dirfilter'])) {
            $taskInfo['additionalscheduler_dirfilter'] = $parentObject->CMD == 'edit' ? $task->dirfilter : '';
        }
        $additionalFields = [];
        $fieldID = 'task_nbdays';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_nbdays]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_nbdays'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:nbdays',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];
        $fieldID = 'task_dirfilter';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_dirfilter]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_dirfilter'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:dirfilter',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];
        return $additionalFields;
    }

    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
    {
        $result = true;
        if (!isset($submittedData['additionalscheduler_nbdays'])) {
            $this->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:nbdayserror'), FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        $task->nbdays = $submittedData['additionalscheduler_nbdays'];
        $task->dirfilter = $submittedData['additionalscheduler_dirfilter'];
    }
}
