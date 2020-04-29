<?php

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class tx_additionalscheduler_execquery_fields extends \Sng\Additionalscheduler\AdditionalFieldProviderInterface
{

    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {

        if (empty($taskInfo['additionalscheduler_exec_query'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['additionalscheduler_exec_query'] = $task->query;
            } else {
                $taskInfo['additionalscheduler_exec_query'] = '';
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

        if (empty($taskInfo['additionalscheduler_exec_emailtemplate'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['additionalscheduler_exec_emailtemplate'] = $task->emailtemplate;
            } else {
                $taskInfo['additionalscheduler_exec_emailtemplate'] = '';
            }
        }

        $additionalFields = array();

        $fieldID = 'task_path';
        $fieldCode = '<textarea name="tx_scheduler[additionalscheduler_exec_query]" id="' . $fieldID . '" cols="50" rows="10" />' . $taskInfo['additionalscheduler_exec_query'] . '</textarea>';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:query',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        );

        $fieldID = 'task_subject';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_subject]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_subject'] . '" size="50" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:subject',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        );

        $fieldID = 'task_email';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_email]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_email'] . '" size="50" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:email',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        );



        $fieldID = 'task_emailtemplate';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_emailtemplate]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_emailtemplate'] . '" size="50" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:emailtemplate',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        );

        return $additionalFields;
    }

    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        $result = true;
        if (empty($submittedData['additionalscheduler_exec_query'])) {
            $parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:savedirerror'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task)
    {
        $task->query = $submittedData['additionalscheduler_exec_query'];
        $task->email = $submittedData['additionalscheduler_exec_email'];
        $task->subject = $submittedData['additionalscheduler_exec_subject'];
        $task->emailtemplate = $submittedData['additionalscheduler_exec_emailtemplate'];
    }

}
