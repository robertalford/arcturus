<?php
 
include './config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// GET the data
$sql = "SELECT * FROM messages";
$result = $conn->query($sql);

echo $result;

?>
