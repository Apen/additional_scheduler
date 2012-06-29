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

class tx_additionalscheduler_cleart3temp_fields implements tx_scheduler_AdditionalFieldProvider
{

	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {

		if (empty($taskInfo['additionalscheduler_nbdays'])) {
			if ($parentObject->CMD == 'edit') {
				$taskInfo['additionalscheduler_nbdays'] = $task->nbdays;
			} else {
				$taskInfo['additionalscheduler_nbdays'] = '';
			}
		}
		
		if (empty($taskInfo['additionalscheduler_dirfilter'])) {
			if ($parentObject->CMD == 'edit') {
				$taskInfo['additionalscheduler_dirfilter'] = $task->dirfilter;
			} else {
				$taskInfo['additionalscheduler_dirfilter'] = '';
			}
		}

		$additionalFields = array();

		$fieldID = 'task_nbdays';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_nbdays]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_nbdays'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:nbdays',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);
		
		$fieldID = 'task_dirfilter';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_dirfilter]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_dirfilter'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:dirfilter',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);

		return $additionalFields;
	}

	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$result = TRUE;
		if (!isset($submittedData['additionalscheduler_nbdays'])) {
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:nbdayserror'), t3lib_FlashMessage::ERROR);
			$result = FALSE;
		}
		return $result;
	}

	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->nbdays = $submittedData['additionalscheduler_nbdays'];
		$task->dirfilter = $submittedData['additionalscheduler_dirfilter'];
	}
}

?>
