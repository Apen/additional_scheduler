<?php

$tasks = array('savewebsite', 'translationupdate', 'exec');
$loadArray = array();
$extensionPath = t3lib_extMgm::extPath('additional_scheduler');

foreach ($tasks as $task) {
	$loadArray['tx_additionalscheduler_' . $task] = $extensionPath . 'tasks/class.tx_additionalscheduler_' . $task . '.php';
	$loadArray['tx_additionalscheduler_' . $task . '_fields'] = $extensionPath . 'tasks/class.tx_additionalscheduler_' . $task . '_fields.php';
}

return $loadArray;

?>