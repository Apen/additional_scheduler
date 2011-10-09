<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$tasks = array('savewebsite', 'translationupdate', 'exec');

foreach ($tasks as $task) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_additionalscheduler_' . $task] = array(
		'extension' => $_EXTKEY,
		'title' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:task.' . $task . '.name',
		'description' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:task.' . $task . '.description',
		'additionalFields' => 'tx_additionalscheduler_' . $task . '_fields'
	);
}

?>