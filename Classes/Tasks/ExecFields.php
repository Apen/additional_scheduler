<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class ExecFields extends \Sng\Additionalscheduler\AdditionalFieldProviderInterface
{
    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        if (empty($taskInfo['additionalscheduler_exec_path'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['additionalscheduler_exec_path'] = $task->path;
            } else {
                $taskInfo['additionalscheduler_exec_path'] = '';
            }
        }

        if (empty($taskInfo['additionalscheduler_exec_subject'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['additionalscheduler_exec_subject'] = $task->subject;
            } else {
                $taskInfo['additionalscheduler_exec_subject'] = '';
            }
        }

        if (empty($taskInfo['additionalscheduler_exec_email'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['additionalscheduler_exec_email'] = $task->email;
            } else {
                $taskInfo['additionalscheduler_exec_email'] = '';
            }
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

    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        $result = true;
        if (empty($submittedData['additionalscheduler_exec_path'])) {
            $parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:savedirerror'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            $result = false;
        }
        // check script is executable
        $script = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(' ', $submittedData['additionalscheduler_exec_path']);
        if ($script[0]{0} != '/') {
            $script[0] = PATH_site . $script[0];
        }
        if (!empty($script[0]) && is_executable($script[0]) === false) {
            $parentObject->addMessage(sprintf($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:mustbeexecutable'), $submittedData['additionalscheduler_exec_path']), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task)
    {
        $task->path = $submittedData['additionalscheduler_exec_path'];
        $task->email = $submittedData['additionalscheduler_exec_email'];
        $task->subject = $submittedData['additionalscheduler_exec_subject'];
    }
}
