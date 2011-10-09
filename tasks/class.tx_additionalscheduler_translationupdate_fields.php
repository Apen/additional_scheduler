<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 CERDAN Yohann (cerdanyohann@yahoo.fr)
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

class tx_additionalscheduler_translationupdate_fields implements tx_scheduler_AdditionalFieldProvider
{

	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {

		if (empty($taskInfo['additionalscheduler_lang'])) {
			if ($parentObject->CMD == 'edit') {
				$taskInfo['additionalscheduler_lang'] = $task->lang;
			} else {
				$taskInfo['additionalscheduler_lang'] = '';
			}
		}

		$additionalFields = array();

		$fieldID = 'task_lang';
		$fieldCode = '<input type="text" name="tx_scheduler[additionalscheduler_lang]" id="' . $fieldID . '" value="' . $taskInfo['additionalscheduler_lang'] . '" size="50" />';
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:additional_scheduler/locallang.xml:lang',
			'cshKey' => 'additional_scheduler',
			'cshLabel' => $fieldID
		);

		return $additionalFields;
	}

	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$result = TRUE;
		if (empty($submittedData['additionalscheduler_lang'])) {
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:langerror'), t3lib_FlashMessage::ERROR);
			$result = FALSE;
		}
		return $result;
	}

	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->lang = $submittedData['additionalscheduler_lang'];
	}
}

?>
