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

class tx_additionalscheduler_translationupdate extends tx_scheduler_Task
{
	public $em = NULL;
	public $settings = NULL;
	public $terConnection = NULL;

	public function execute() {
		global $TYPO3_LOADED_EXT;

		$this->initEM();

		$loadedExtensions = array_keys($TYPO3_LOADED_EXT);
		$loadedExtensions = array_diff($loadedExtensions, array('_CACHEFILE'));

		$langs = explode(',', $this->lang);

		foreach ($loadedExtensions as $extKey) {
			foreach ($langs as $lang) {
				$status = $this->updateTranslation($extKey, $lang);
			}
		}

		return TRUE;
	}

	public function initEM() {
		require_once(PATH_site . 'typo3/template.php');
		if (t3lib_div::int_from_ver(TYPO3_version) <= 4005000) {
			require_once(PATH_site . 'typo3/mod/tools/em/class.em_index.php');
			$this->em = t3lib_div::makeInstance('SC_mod_tools_em_index');
			$this->em->init();
		} else {
			require_once(PATH_site . 'typo3/sysext/em/classes/extensions/class.tx_em_extensions_list.php');
			require_once(PATH_site . 'typo3/sysext/em/classes/extensions/class.tx_em_extensions_details.php');
			require_once(PATH_site . 'typo3/sysext/em/classes/tools/class.tx_em_tools_xmlhandler.php');
			require_once(PATH_site . 'typo3/sysext/em/classes/settings/class.tx_em_settings.php');
			$this->em = t3lib_div::makeInstance('tx_em_Extensions_List');
			$this->settings = t3lib_div::makeInstance('tx_em_Settings');
		}
	}

	public function getMirrorURL() {
		global $TYPO3_CONF_VARS;
		if (t3lib_div::int_from_ver(TYPO3_version) <= 4005000) {
			$this->em->MOD_SETTINGS['mirrorListURL'] = $TYPO3_CONF_VARS['EXT']['em_mirrorListURL'];
			return $this->em->getMirrorURL();
		} else {
			return $this->settings->getMirrorURL();
		}
	}

	public function updateTranslation($extKey, $lang) {
		//$mirrorURL = $this->getMirrorURL();
		$mirrorURL = 'http://typo3.org/fileadmin/ter/';
		if (t3lib_div::int_from_ver(TYPO3_version) <= 4005000) {
			return $this->em->updateTranslation($extKey, $lang, $mirrorURL);
		} else {
			$this->terConnection = t3lib_div::makeInstance('tx_em_Connection_Ter');
			$translation = t3lib_div::makeInstance('tx_em_Translations', $this);
			return $translation->updateTranslation($extKey, $lang, $mirrorURL);
		}
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
		return $this->lang;
	}

}

?>
