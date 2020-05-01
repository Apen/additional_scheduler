<?php

use Sng\Additionalscheduler\Utils;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$extensionPath = ExtensionManagementUtility::extPath('additional_scheduler');
$tasks = Utils::getTasksList();

foreach ($tasks as $task) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Sng\\Additionalscheduler\\Tasks\\' . $task . 'Task'] = [
        'extension'        => 'additional_scheduler',
        'title'            => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . strtolower($task) . '.name',
        'description'      => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . strtolower($task) . '.description',
        'additionalFields' => 'Sng\\Additionalscheduler\\Tasks\\' . $task . 'Fields'
    ];
}
