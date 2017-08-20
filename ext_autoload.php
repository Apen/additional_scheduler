<?php

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('additional_scheduler');

require_once($extensionPath . 'Classes/Utils.php');
require_once($extensionPath . 'Classes/Templating.php');
require_once($extensionPath . 'Classes/AdditionalFieldProviderInterface.php');

$tasks = \Sng\Additionalscheduler\Utils::getTasksList();
$loadArray = array();

foreach ($tasks as $task) {
    $loadArray['tx_additionalscheduler_' . $task] = $extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '.php';
    $loadArray['tx_additionalscheduler_' . $task . '_fields'] = $extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '_fields.php';
}

return $loadArray;
