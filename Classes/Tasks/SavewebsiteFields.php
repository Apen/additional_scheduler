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

class SavewebsiteFields extends AdditionalFieldProviderInterface
{
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {
        if (empty($taskInfo['additionalscheduler_savewebsite_path'])) {
            $taskInfo['additionalscheduler_savewebsite_path'] = $parentObject->CMD == 'edit' ? $task->path : '';
        }

        if (empty($taskInfo['additionalscheduler_exec_subject'])) {
            $taskInfo['additionalscheduler_exec_subject'] = $parentObject->CMD == 'edit' ? $task->subject : '';
        }

        if (empty($taskInfo['additionalscheduler_savewebsite_email'])) {
            $taskInfo['additionalscheduler_savewebsite_email'] = $parentObject->CMD == 'edit' ? $task->email : '';
        }

        $additionalFields = [];

        $fieldID = 'task_path';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_path]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_path'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:savedir',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];
        $fieldID = 'task_subject';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_subject]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_subject'] . '" size="50" />';
        $additionalFields[$fieldID] = [
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:subject',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        ];
        $fieldID = 'task_email';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_email]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_email'] . '" size="50" />';
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
        // check dir is writable
        if ((empty($submittedData['additionalscheduler_savewebsite_path'])) || (!is_writable($submittedData['additionalscheduler_savewebsite_path']))) {
            $parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:savedirerror'), FlashMessage::ERROR);
            $result = false;
        }
        // check save script is executable
        $saveScript = PATH_site . 'typo3conf/ext/additional_scheduler/Resources/Shell/save_typo3_website.sh';
        if (!is_executable($saveScript)) {
            $parentObject->addMessage(sprintf($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:mustbeexecutable'), $saveScript), FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        $task->path = $submittedData['additionalscheduler_savewebsite_path'];
        $task->email = $submittedData['additionalscheduler_savewebsite_email'];
        $task->subject = $submittedData['additionalscheduler_savewebsite_subject'];
    }
}
