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

class tx_additionalscheduler_clearcache extends tx_scheduler_Task
{

	/**
	 * Executes the commit task and returns TRUE if the execution was
	 * succesfull
	 *
	 * @return	boolean	returns TRUE on success, FALSE on failure
	 */

	public function execute() {
		$GLOBALS['BE_USER']->user['admin'] = 1;
		$tce = t3lib_div::makeInstance('t3lib_TCEmain');
		$tce->start(Array(), Array());
		$tce->clear_cacheCmd('all');
		return TRUE;
	}

}

?>
