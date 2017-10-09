<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class tx_additionalscheduler_savewebsite_fields extends \Sng\Additionalscheduler\AdditionalFieldProviderInterface
{

    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {

        if (empty($taskInfo['additionalscheduler_savewebsite_path'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['additionalscheduler_savewebsite_path'] = $task->path;
            } else {
                $taskInfo['additionalscheduler_savewebsite_path'] = '';
            }
        }

        if (empty($taskInfo['additionalscheduler_savewebsite_email'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['additionalscheduler_savewebsite_email'] = $task->email;
            } else {
                $taskInfo['additionalscheduler_savewebsite_email'] = '';
            }
        }

        $additionalFields = array();

        $fieldID = 'task_path';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_path]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_path'] . '" size="50" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:savedir',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        );

        $fieldID = 'task_email';
        $fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_email]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_email'] . '" size="50" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:email',
            'cshKey'   => 'additional_scheduler',
            'cshLabel' => $fieldID
        );

        return $additionalFields;
    }

    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        $result = true;
        // check dir is writable
        if ((empty($submittedData['additionalscheduler_savewebsite_path'])) || (is_writable($submittedData['additionalscheduler_savewebsite_path']) === false)) {
            $parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:savedirerror'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            $result = false;
        }
        // check save script is executable
        $saveScript = PATH_site . 'typo3conf/ext/additional_scheduler/Resources/Shell/save_typo3_website.sh';
        if (is_executable($saveScript) === false) {
            $parentObject->addMessage(sprintf($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:mustbeexecutable'), $saveScript), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task)
    {
        $task->path = $submittedData['additionalscheduler_savewebsite_path'];
        $task->email = $submittedData['additionalscheduler_savewebsite_email'];
    }
}
