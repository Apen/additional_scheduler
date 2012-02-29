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

class tx_additionalscheduler_exec_fields implements tx_scheduler_AdditionalFieldProvider
{

	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {

		if (empty($taskInfo['additionalscheduler_exec_path'])) {
			if ($parentObject->CMD == 'edit') {
				$taskInfo['additionalscheduler_exec_path'] = $task->path;
			} else {
				$taskInfo['additionalscheduler_exec_path'] = '';
			}
		}

		if (empty($taskInfo['additionalscheduler_exec_email'])) {
			if ($parentObject->CMD == 'edit') {
				$taskInfo['additionalscheduler_exec_email'] = $task->email;
			} else {
				$taskInfo['additionalscheduler_exec_email'] = '';
			}
		}

		if (empty($taskInfo['additionalscheduler_exec_emailfrom'])) {
			if ($parentObject->CMD == 'edit') {
				$taskInfo['additionalscheduler_exec_emailfrom'] = $task->emailfrom;
			} else {
				$taskInfo['additionalscheduler_exec_emailfrom'] = '';
			}
		}

		$additionalFields = array();

		$fieldID = 'task_path';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_path]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_path'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:execdir',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);

		$fieldID = 'task_email';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_email]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_email'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:email',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);

		$fieldID = 'task_emailfrom';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_exec_emailfrom]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_exec_emailfrom'] . '" size="50" />';
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
		if (empty($submittedData['additionalscheduler_exec_path'])) {
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:savedirerror'), t3lib_FlashMessage::ERROR);
			$result = FALSE;
		}
		return $result;
	}

	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->path = $submittedData['additionalscheduler_exec_path'];
		$task->email = $submittedData['additionalscheduler_exec_email'];
		$task->emailfrom = $submittedData['additionalscheduler_exec_emailfrom'];
	}

}

?>
