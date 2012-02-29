<?php

require_once(t3lib_extMgm::extPath('additional_scheduler') . 'classes/class.tx_additionalscheduler_utils.php');

$tasks = tx_additionalscheduler_utils::getTasksList();
$loadArray = array();
$extensionPath = t3lib_extMgm::extPath('additional_scheduler');

foreach ($tasks as $task) {
	$loadArray['tx_additionalscheduler_' . $task] = $extensionPath . 'tasks/class.tx_additionalscheduler_' . $task . '.php';
	$loadArray['tx_additionalscheduler_' . $task . '_fields'] = $extensionPath . 'tasks/class.tx_additionalscheduler_' . $task . '_fields.php';
}

return $loadArray;

?>