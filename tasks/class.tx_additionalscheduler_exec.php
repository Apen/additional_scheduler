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

class tx_additionalscheduler_exec extends tx_scheduler_Task
{

	/**
	 * Executes the commit task and returns TRUE if the execution was
	 * succesfull
	 *
	 * @return	boolean	returns TRUE on success, FALSE on failure
	 */

	public function execute() {

		// exec SH
		if (substr($this->path, 0, 1) == '/') {
			$cmd = $this->path;
		} else {
			$cmd = PATH_site . $this->path;
		}

		$return = shell_exec($cmd . ' 2>&1');

		// mail
		$mailTo = $this->email;
		$mailSubject = '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:task.exec.name');
		$mailBody = $cmd . LF . LF . $return;
		$mailHeaders = 'From: ' . $this->emailfrom . " \r\n" . 'Reply-To: ' . $this->emailfrom . " \r\n";

		if (empty($this->email) !== TRUE) {
			mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
		}

		return TRUE;
	}

	/**
	 * This method is designed to return some additional information about the task,
	 * that may help to set it apart from other tasks from the same class
	 * This additional information is used - for example - in the Scheduler's BE module
	 * This method should be implemented in most task classes
	 *
	 * @return	string	Information to display
	 */

	public function getAdditionalInformation() {
		return $this->path;
	}

}

?>
