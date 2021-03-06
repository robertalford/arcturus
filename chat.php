<?php

include './config.php';


// Redirect to login page is no cookie exists.
if (!isset($_COOKIE['auth'])) {
	header("Location: " . $domain . "/index.php"); /* Redirect browser */
	exit();
}


// Identify current user based on cookie value
// $cookie = $_COOKIE['auth'];
// $mob = $cookie['mob'];
// $name = $cookie['name'];
$mob = $_COOKIE['auth'];
$friendmob = $_GET['friendmob'];
// $name = 'Rob';


// Get the friends list & generate html (server side)
$myfriends = file_get_contents($domain . "/getfriendslist.php?mob=" . $mob);
$myfriends = json_decode($myfriends, true);

$friendcount = 0;
$friendshtml = '';
foreach ($myfriends as $friend) {
	
	if ($friendmob != '') {
		if ($friend['mob'] == $friendmob) {
			$friendshtml2 = $friendshtml2 . '<li id="' . $friend['mob'] . '" class="friend active">' . $friend['name'] . '</li>';
			$activefriendmob = $friend['mob'];
			$activefriendname = $friend['name'];
			$name = $friend['user'];
		} else {
			$friendshtml2 = $friendshtml2 .  '<li id="' . $friend['mob'] . '" class="friend inactive">' . $friend['name'] . '</li>';
		}
	} else {
		$friendcount++;
		if ($friendcount == 1) {
			$friendshtml2 = $friendshtml2 . '<li id="' . $friend['mob'] . '" class="friend active">' . $friend['name'] . '</li>';
			$activefriendmob = $friend['mob'];
			$activefriendname = $friend['name'];
			$name = $friend['user'];
		} else {
			$friendshtml2 = $friendshtml2 .  '<li id="' . $friend['mob'] . '" class="friend inactive">' . $friend['name'] . '</li>';
		}
	}
}

// Calc the datetime for the page load - e.g. last updated
$t = microtime(true);
$micro = sprintf("%06d",($t - floor($t)) * 1000000);
$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
$lastupdated = $d->format("Y-m-d H:i:s.u"); 

// Get the chat log for the active user & generate html (server side)
$chathtml = file_get_contents($domain . "/getallmsgs.php?mob1=" . $mob . "&mob2=" . $activefriendmob . "&name=" . $name . "&activefriendname=" . $activefriendname);

?>



<!DOCTYPE html>
<html>

	<head>
		<title>Project Arcturus - Chat</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="./styles.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

		<script>

		$( document ).ready(function() {
			
			$('#lastupdated').text('<?php echo $lastupdated; ?>');

			$(document).scrollTop($(document).height());

		    $('.messageinput').keypress(function(e){
		      if(e.keyCode==13)
		      $('.inputbutton').click();
		    });


			$( ".friend.inactive" ).click(function() {
				friendmob = $(this).attr('id');
				//console.log(friendmob);
				window.location.href = "./chat.php?friendmob=" + friendmob;
			});


			$( ".inputbutton" ).click(function() {
				message = $('.messageinput').val();
				$('.messageinput').val('');

				$.post("addmsg.php",
			    {
			        frommob: "<?php echo $mob; ?>",
			        tomob: "<?php echo $activefriendmob; ?>",
			        msg: message
			    })

				chatcontent = $('#chat-content').html();
				newcontent = '<p class="chatmsg fromme"><span class="bold"> <?php echo $name; ?>: </span>' + message + '</p>';
				newchatcontent = chatcontent + newcontent;
				$('#chat-content').html(newchatcontent);
				$(document).scrollTop($(document).height());
			});

			refreshChat();	


			function getNewMsgs(){

				name = '<?php echo $name; ?>';
				mob = '<?php echo $mob; ?>';
				activefriendmob = '<?php echo $activefriendmob; ?>';
				activefriendname = '<?php echo $activefriendname; ?>';

				$.get( "/getallmsgs.php?mob1=" + mob + "&mob2=" + activefriendmob + "&name=" + name + "&activefriendname=" + activefriendname, function( data ) {
					$('#chat-content').html(data);
					
				},'html');	

			   	$(document).scrollTop($(document).height());
			    
  
			}	

			function refreshChat(){

				mob = '<?php echo $mob; ?>';

				$.get( "/newmsgcheck.php?tomob=" + mob, function( data ) {
					if (data == '1') {
						getNewMsgs();
					}
				});	
			    
			    setTimeout(refreshChat, 1000);
  
			}



		});

		</script>


	</head>

	<body>

	<div class="page-wrap">

		<nav class="navbar navbar-inverse navbar-fixed-top">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">Project Arcturus - Chat App</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav navbar-right">
		      <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $activefriendname; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
			<?php echo $friendshtml2; ?> 		
          </ul>
        </li>
		        <li><a href="./index.php?type=logout">Log out</a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>



		<div class="container">

		  	<div class="row containerrow">

				<div id="chat-content">
			  		<?php
			  			echo $chathtml;
			  		?>
			  	</div>

		</div>
	
	</div> <!-- end page wrap -->

	<div class="footer">
		<div class="container">	
		    <input class="messageinput" type="text" name="msg" placeholder="Type your message here...">
		    <button type="submit" class="inputbutton btn btn-default">Send</button>
		</div>

	</div>
					

	</body>

</html>