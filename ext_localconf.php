<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('additional_scheduler');

require_once($extensionPath . 'Classes/Utils.php');
require_once($extensionPath . 'Classes/AdditionalFieldProviderInterface.php');
$tasks = \Sng\Additionalscheduler\Utils::getTasksList();

foreach ($tasks as $task) {
    require_once($extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '.php');
    require_once($extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '_fields.php');
}

foreach ($tasks as $task) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_additionalscheduler_' . $task] = array(
        'extension'        => $_EXTKEY,
        'title'            => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:task.' . $task . '.name',
        'description'      => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:task.' . $task . '.description',
        'additionalFields' => 'tx_additionalscheduler_' . $task . '_fields'
    );
}
