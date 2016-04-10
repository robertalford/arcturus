<?php

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

$conn->close();

echo $result;

?>