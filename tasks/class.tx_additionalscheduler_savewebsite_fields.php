<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 CERDAN Yohann (cerdanyohann@yahoo.fr)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class tx_additionalscheduler_savewebsite_fields implements tx_scheduler_AdditionalFieldProvider
{

	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {

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

		if (empty($taskInfo['additionalscheduler_savewebsite_emailfrom'])) {
			if ($parentObject->CMD == 'edit') {
				$taskInfo['additionalscheduler_savewebsite_emailfrom'] = $task->emailfrom;
			} else {
				$taskInfo['additionalscheduler_savewebsite_emailfrom'] = '';
			}
		}

		$additionalFields = array();

		$fieldID = 'task_path';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_path]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_path'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:savedir',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);

		$fieldID = 'task_email';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_email]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_email'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:email',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);

		$fieldID = 'task_emailfrom';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_savewebsite_emailfrom]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_savewebsite_emailfrom'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:emailfrom',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);

		return $additionalFields;
	}

	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$result = true;
		// check dir is writable
		if ((empty($submittedData['additionalscheduler_savewebsite_path'])) || (is_writable($submittedData['additionalscheduler_savewebsite_path']) === FALSE)) {
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:savedirerror'), t3lib_FlashMessage::ERROR);
			$result = FALSE;
		}
		// check save script is executable
		$saveScript = PATH_site . 'typo3conf/ext/additional_scheduler/sh/save_typo3_website.sh';
		if (is_executable($saveScript) === FALSE) {
			$parentObject->addMessage(sprintf($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:mustbeexecutable'), $saveScript), t3lib_FlashMessage::ERROR);
			$result = FALSE;
		}
		return $result;
	}

	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->path = $submittedData['additionalscheduler_savewebsite_path'];
		$task->email = $submittedData['additionalscheduler_savewebsite_email'];
		$task->emailfrom = $submittedData['additionalscheduler_savewebsite_emailfrom'];
	}

}

?>
