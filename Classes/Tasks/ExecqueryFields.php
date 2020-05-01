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

class ExecqueryFields extends AdditionalFieldProviderInterface
{
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {
        if (empty($taskInfo['additionalscheduler_exec_query'])) {
            $taskInfo['additionalscheduler_exec_query'] = $parentObject->CMD == 'edit' ? $task->query : '';
        }

        if (empty($taskInfo['additionalscheduler_exec_subject'])) {
            $taskInfo['additionalscheduler_exec_subject'] = $parentObject->CMD == 'edit' ? $task->subject : '';
        }

        if (empty($taskInfo['additionalscheduler_exec_email'])) {
            $taskInfo['additionalscheduler_exec_email'] = $parentObject->CMD == 'edit' ? $task->email : '';
        }

        if (empty($taskInfo['additionalscheduler_exec_emailtemplate'])) {
            $taskInfo['additionalscheduler_exec_emailtemplate'] = $parentObject->CMD == 'edit' ? $task->emailtemplate : '';
        }

        $additionalFields = [];

        $fieldID = 'task_path';
        $fieldCode = '<textarea name="tx_scheduler[additionalscheduler_exec_query]" id="' . $fieldID . '" cols="50" rows="10" />' . $taskInfo['additionalscheduler_exec_query'] . '</textarea>';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:query',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];

        $fieldID = 'task_subject';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_subject]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_subject'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:subject',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];

        $fieldID = 'task_email';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_email]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_email'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:email',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];

        $fieldID = 'task_emailtemplate';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_emailtemplate]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_emailtemplate'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:emailtemplate',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];

        return $additionalFields;
    }

    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
    {
        $result = true;
        if (empty($submittedData['additionalscheduler_exec_query'])) {
            $parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:savedirerror'), FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        $task->query = $submittedData['additionalscheduler_exec_query'];
        $task->email = $submittedData['additionalscheduler_exec_email'];
        $task->subject = $submittedData['additionalscheduler_exec_subject'];
        $task->emailtemplate = $submittedData['additionalscheduler_exec_emailtemplate'];
    }
}
