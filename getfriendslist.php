<?php

header('Content-Type: application/json');

include './config.php';

$mob = $_GET['mob'];

$list = array();

foreach ($friendslist as $account) {
	if ($account['mob'] == $mob) {

		foreach ($account['friends'] as $friend) {
			$afriend['name'] = $friend['name'];
			$afriend['mob'] = $friend['mob'];
			array_push($list, $afriend);
		}

	}
}

$result = json_encode($list);

echo $result;

?>