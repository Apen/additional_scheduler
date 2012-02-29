<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

require_once(t3lib_extMgm::extPath('additional_scheduler') . 'classes/class.tx_additionalscheduler_utils.php');
$tasks = tx_additionalscheduler_utils::getTasksList();

foreach ($tasks as $task) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_additionalscheduler_' . $task] = array(
		'extension' => $_EXTKEY,
		'title' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:task.' . $task . '.name',
		'description' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:task.' . $task . '.description',
		'additionalFields' => 'tx_additionalscheduler_' . $task . '_fields'
	);
}

?>