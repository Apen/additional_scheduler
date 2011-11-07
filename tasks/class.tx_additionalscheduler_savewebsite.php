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

class tx_additionalscheduler_savewebsite extends tx_scheduler_Task
{

	public function execute() {
		require_once(PATH_site . 'typo3conf/ext/additional_scheduler/classes/class.tx_additionalscheduler_utils.php');

		// exec SH
		$saveScript = PATH_site . 'typo3conf/ext/additional_scheduler/sh/save_typo3_website.sh';
		$cmd = $saveScript . ' -p ' . PATH_site . ' -o ' . $this->path . ' -f';
		$return = shell_exec($cmd . ' 2>&1');

		// mail
		$mailTo = $this->email;
		$mailSubject = '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/locallang.xml:task.savewebsite.name');
		$mailBody = $cmd . LF . LF . $return;

		if (empty($this->email) !== TRUE) {
			tx_additionalscheduler_utils::sendEmail($mailTo, $mailSubject, $mailBody, 'plain', $this->emailfrom, $this->emailfrom, 'utf-8');
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
