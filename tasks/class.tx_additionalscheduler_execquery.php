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

class tx_additionalscheduler_execquery extends tx_scheduler_Task
{

	/**
	 * Executes the commit task and returns TRUE if the execution was
	 * succesfull
	 *
	 * @return    boolean    returns TRUE on success, FALSE on failure
	 */

	public function execute() {
		require_once(PATH_site . 'typo3conf/ext/additional_scheduler/classes/class.tx_additionalscheduler_utils.php');

		// templating
		$template = new tx_additionalscheduler_templating();
		if (!empty($this->emailtemplate)) {
			$template->initTemplate($this->emailtemplate);
		} else {
			$template->initTemplate('typo3conf/ext/additional_scheduler/res/templates/execquery.html');
		}
		$markersArray = array();

		// exec query
		$res = $GLOBALS['TYPO3_DB']->sql_query($this->query);
		$i = 0;
		$return = '';

		$return .= '<table>';

		while ($item = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if ($i === 0) {
				$return .= '<thead>';
				$return .= '<tr>';
				foreach ($item as $itemKey => $itemValue) {
					$return .= '<th>' . $itemKey . '</th>';
				}
				$return .= '</tr>';
				$return .= '</thead>';
				$return .= '<tbody>';
			}
			$return .= '<tr>';
			foreach ($item as $itemKey => $itemValue) {
				$return .= '<td>' . $itemValue . '</td>';
			}
			$return .= '</tr>';
			$i++;
		}

		$return .= '</tbody>';
		$return .= '</table>';

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		$markersArray['###MAIL_CONTENT###'] = $return;
		$mailcontent = $template->renderAllTemplate($markersArray, '###EMAIl_TEMPLATE###');
		preg_match('/<title\>(.*?)<\/title>/', $mailcontent, $matches);

		// mail
		$mailTo = $this->email;
		$mailSubject = '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:task.execquery.name');
		if (!empty($matches[1])) {
			$mailSubject = $matches[1];
		}

		if (empty($this->email) !== TRUE) {
			tx_additionalscheduler_utils::sendEmail($mailTo, $mailSubject, $mailcontent, 'html', $this->emailfrom, $this->emailfrom, 'utf-8');
		}

		return TRUE;
	}

	/**
	 * This method is designed to return some additional information about the task,
	 * that may help to set it apart from other tasks from the same class
	 * This additional information is used - for example - in the Scheduler's BE module
	 * This method should be implemented in most task classes
	 *
	 * @return    string    Information to display
	 */

	public function getAdditionalInformation() {
		return substr($this->query, 0, 30);
	}

}

?>
