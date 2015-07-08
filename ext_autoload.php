<?php

require_once(t3lib_extMgm::extPath('additional_scheduler') . 'Classes/Utils.php');
require_once(t3lib_extMgm::extPath('additional_scheduler') . 'Classes/Templating.php');
require_once(t3lib_extMgm::extPath('additional_scheduler') . 'Classes/AdditionalFieldProviderInterface.php');

$tasks = \Sng\Additionalscheduler\Utils::getTasksList();
$loadArray = array();
$extensionPath = t3lib_extMgm::extPath('additional_scheduler');

foreach ($tasks as $task) {
	$loadArray['tx_additionalscheduler_' . $task] = $extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '.php';
	$loadArray['tx_additionalscheduler_' . $task . '_fields'] = $extensionPath . 'Classes/Tasks/class.tx_additionalscheduler_' . $task . '_fields.php';
}

return $loadArray;

?>