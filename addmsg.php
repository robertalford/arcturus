<?php

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

// Get the mob of the two users in chat
$frommob = $_POST['frommob'];
$tomob = $_POST['tomob'];
$msg = $_POST['msg'];

// Calc the datetime
$t = microtime(true);
$micro = sprintf("%06d",($t - floor($t)) * 1000000);
$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
$datetime = $d->format("Y-m-d H:i:s.u"); 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// SQL to write full msg content to database
$sql1 = "INSERT INTO messages (frommob, tomob, datetime, msg)
VALUES ('$frommob', '$tomob', '$datetime', '$msg')";

// SQL to add new message alert to Q
$sql2 = "INSERT INTO newmsgq (tomob, datetime)
VALUES ('$tomob', '$datetime')";

if ($conn->query($sql1) === TRUE) {
    echo "Full msg added to DB";
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

if ($conn->query($sql2) === TRUE) {
    echo "Msg added to newMsgQ";
} else {
    echo "Error: " . $sql2 . "<br>" . $conn->error;
}

$conn->close();

?>