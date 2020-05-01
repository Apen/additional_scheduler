<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('additional_scheduler');
$tasks = \Sng\Additionalscheduler\Utils::getTasksList();

foreach ($tasks as $task) {
    require_once($extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '.php');
    require_once($extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '_fields.php');
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_additionalscheduler_' . $task] = [
        'extension'        => 'additional_scheduler',
        'title'            => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . $task . '.name',
        'description'      => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . $task . '.description',
        'additionalFields' => 'tx_additionalscheduler_' . $task . '_fields'
    ];
}
