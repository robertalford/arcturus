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
$mob1 = $_GET['mob1'];
$mob2 = $_GET['mob2'];
$name = $_GET['name'];
$activefriendname = $_GET['activefriendname'];
$lastupdated = $_GET['lastupdated'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// GET the data
if ($lastupdated == '') {
    $sql = "SELECT frommob, tomob, datetime, msg FROM messages WHERE (frommob = '$mob1' or frommob = '$mob2') and (tomob = '$mob1' or tomob = '$mob2')";
} else {
    $sql = "SELECT frommob, tomob, datetime, msg FROM messages WHERE (frommob = '$mob1' or frommob = '$mob2') and (tomob = '$mob1' or tomob = '$mob2') and datetime > $lastupdated";
}

$result = $conn->query($sql);

// Create an array to hold the data
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
    echo "";
}
$conn->close();



function cmp($a,$b){
    return strtotime($a['datetime']) > strtotime($b['datetime']) ? 1 : -1;
};
uasort($getallmsgs,'cmp');

$chathtml = '';
foreach ($getallmsgs as $message) {
	if ($message['frommob'] == $mob1) {
		$chathtml = $chathtml . '<p class="chatmsg fromme"><span class="bold">' . $name . ': </span>' . $message['msg'] . '</p>';
	} else {
		$chathtml = $chathtml . '<p class="chatmsg tome"><span class="bold">' . $activefriendname . ': </span>' . $message['msg'] . '</p>';
	}
}

echo $chathtml;

?>