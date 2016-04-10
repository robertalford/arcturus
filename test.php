<?php

header('Content-Type: application/json');

$domain = 'http://' . $_SERVER['SERVER_NAME'];

if ($domain == 'http://localhost') {
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "arcturus";
} else {
	$servername = "us-cdbr-iron-east-03.cleardb.net";
	$username = "b362c3d1b6669a";
	$password = "1a6a7653";
	$dbname = "heroku_9d5761ca16500a6";
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
}

// GET the data
$sql = "SELECT * FROM messages";
$result = $conn->query($sql);

$getallmsgs = array();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$record['frommob'] = $row['frommob'];
    	$record['tomob'] = $row['tomob'];
    	$record['datetime'] = $row['datetime'];
    	$record['msg'] = $row['msg'];
        array_push($getallmsgs, $record);
    }
} else {
    echo "0";
}

echo json_encode($getallmsgs);

?>