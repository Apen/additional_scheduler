<?php



if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('additional_scheduler');
$tasks = \Sng\Additionalscheduler\Utils::getTasksList();

foreach ($tasks as $task) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Sng\\Additionalscheduler\\Tasks\\' . $task . 'Task'] = [
        'extension'        => 'additional_scheduler',
        'title'            => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . strtolower($task) . '.name',
        'description'      => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf:task.' . strtolower($task) . '.description',
        'additionalFields' => 'Sng\\Additionalscheduler\\Tasks\\' . $task . 'Fields'
    ];
}

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['additional_scheduler-additional_scheduler'] =
        \Sng\Additionalscheduler\Command\AdditionalSchedulerCommandController::class;
}
