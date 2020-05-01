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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class ExecFields extends AdditionalFieldProviderInterface
{
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {
        if (empty($taskInfo['additionalscheduler_exec_path'])) {
            $taskInfo['additionalscheduler_exec_path'] = $parentObject->CMD == 'edit' ? $task->path : '';
        }

        if (empty($taskInfo['additionalscheduler_exec_subject'])) {
            $taskInfo['additionalscheduler_exec_subject'] = $parentObject->CMD == 'edit' ? $task->subject : '';
        }

        if (empty($taskInfo['additionalscheduler_exec_email'])) {
            $taskInfo['additionalscheduler_exec_email'] = $parentObject->CMD == 'edit' ? $task->email : '';
        }
        $additionalFields = [];
        $fieldID = 'task_path';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_path]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_path'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:execdir',
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
        return $additionalFields;
    }

    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
    {
        $result = true;
        if (empty($submittedData['additionalscheduler_exec_path'])) {
            $parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:savedirerror'), FlashMessage::ERROR);
            $result = false;
        }
        // check script is executable
        $script = GeneralUtility::trimExplode(' ', $submittedData['additionalscheduler_exec_path']);
        if ($script[0]{0} != '/') {
            $script[0] = PATH_site . $script[0];
        }
        if (!empty($script[0]) && !is_executable($script[0])) {
            $parentObject->addMessage(sprintf($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:mustbeexecutable'), $submittedData['additionalscheduler_exec_path']), FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        $task->path = $submittedData['additionalscheduler_exec_path'];
        $task->email = $submittedData['additionalscheduler_exec_email'];
        $task->subject = $submittedData['additionalscheduler_exec_subject'];
    }
}
