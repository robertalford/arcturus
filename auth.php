<?php

include './config.php';

$mob = $_GET['mob'];
$pin = $_GET['pin'];

// Set default value to false.
$result = 'false';

// Check inputs against array, and set true if match.
foreach ($accounts as $account) {	
	if ($account['mob'] == $mob && $account['pin'] == $pin) {
		$result = 'true';
	}
}

// Return the result
echo $result;

?>