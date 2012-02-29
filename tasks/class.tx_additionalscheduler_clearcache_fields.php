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

class tx_additionalscheduler_clearcache_fields implements tx_scheduler_AdditionalFieldProvider
{

	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {
	}

	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		return TRUE;
	}

	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
	}

}

?>
