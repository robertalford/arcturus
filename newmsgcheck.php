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

$tomob = $_GET['tomob'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// GET the data
$sql = "SELECT tomob, datetime FROM newmsgq WHERE tomob = $tomob";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$result = '1';
} else {
    $result = '0';
}

// TODO: Remove the records from the newmsgq
$sql2= "DELETE * FROM newmsgq WHERE tomob = $tomob";
$result2 = $conn->query($sql2);

$conn->close();

echo $result;

?>