<?php


$domain = 'http://' . $_SERVER['SERVER_NAME'];

// $servername = "us-cdbr-iron-east-03.cleardb.net";
// $username = "b362c3d1b6669a";
// $password = "1a6a7653";
// $dbname = "heroku_9d5761ca16500a6";

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "arcturus";

//mysql://b362c3d1b6669a:1a6a7653@us-cdbr-iron-east-03.cleardb.net/heroku_9d5761ca16500a6?reconnect=true


// The temporary list of accounts
$accounts = array(
    array(
        'name' => 'Rob',
        'mob' => '0431055404',
        'pin' => '6427'
    ),
    // array(
    //     'name' => 'Kat',
    //     'mob' => '0412319312',
    //     'pin' => '5764'
    // ),
    array(
        'name' => 'Charlie',
        'mob' => '0419854780',
        'pin' => '4928'
    )   
);

// The temporary list of friends
$friendslist = array(
    array(
        'name' => 'Rob',
        'mob' => '0431055404',
        'friends' => array(
    // 		array(
				// 'name' => 'Kat',
    //     		'mob' => '0412319312'
    // 		),
    		array(
				'name' => 'Chum',
        		'mob' => '0419854780'
    		)        		
        )
    ),
    // array(
    //     'name' => 'Kat',
    //     'mob' => '0412319312',
    //     'friends' => array(
    // 		array(
				// 'name' => 'Rob',
    //     		'mob' => '0431055404'
    // 		)
    // 	)
    // ),
    array(
        'name' => 'Charlie',
        'mob' => '0419854780',
        'friends' => array(
    		array(
				'name' => 'Rob',
        		'mob' => '0431055404'
    		)
    	)
    )   
);

$userMsgLog = file_get_contents($domain. '/usermsglog.json');
$userMsgLog = json_decode($userMsgLog);

?>