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
$name = 'Rob';


// Get the friends list & generate html (server side)
$myfriends = file_get_contents($domain . "/getfriendslist.php?mob=" . $mob);
$myfriends = json_decode($myfriends, true);

$friendcount = 0;
$friendshtml = '';
foreach ($myfriends as $friend) {
	$friendcount++;
	if ($friendcount == 1) {
		$friendshtml = $friendshtml . '<p class="friend active">' . $friend['name'] . '</p>';
		$activefriendmob = $friend['mob'];
		$activefriendname = $friend['name'];
		$name = $friend['user'];
	} else {
		$friendshtml = $friendshtml .  '<p class="friend inactive">' . $friend['name'] . '</p>';
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

			$('.outer-chatbox').animate({ scrollTop: $('.outer-chatbox').get(0).scrollHeight}, 2000);

		    $('.messageinput').keypress(function(e){
		      if(e.keyCode==13)
		      $('.inputbutton').click();
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
				newcontent = '<p class="chatmsg fromme"><span class="bold"> Rob: </span>' + message + '</p>';
				newchatcontent = chatcontent + newcontent;
				$('#chat-content').html(newchatcontent);
				$('.outer-chatbox').animate({ scrollTop: $('.outer-chatbox').get(0).scrollHeight}, 2000);
			});

			refreshChat();

			function getNewMsgs(){

				name = '<?php echo $name; ?>';
				mob = '<?php echo $mob; ?>';
				activefriendmob = '<?php echo $activefriendmob; ?>';
				activefriendname = '<?php echo $activefriendname; ?>';
				lastupdated = $('#lastupdated').text();
				chathtml = $('#chat-content').html();

				$.get( "/getallmsgs.php?mob1=" + mob + "&mob2=" + activefriendmob + "&name=" + name + "&activefriendname=" + activefriendname, function( data ) {
					if (data == '') {
						// Do nothing
					} else {
						$('#chat-content').html(data);
					}
					//console.log(data);
				});	

				$.get( "/getdatetime.php", function( data ) {
					$('#lastupdated').text(data);
				});	

				$('.outer-chatbox').animate({ scrollTop: $('.outer-chatbox').get(0).scrollHeight}, 2000);
  
			}			

			function refreshChat(){

				mob = '<?php echo $mob; ?>';
				$.get( "/newmsgcheck.php?tomob=" + mob, function( data ) {
					if (data == '1') {
						getNewMsgs();
						//console.log('There are new message... go and get them!!');
					}
				});	
			    
			    setTimeout(refreshChat, 2000);
  
			}



		});

		</script>


	</head>

	<body>
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
		        <li><a href="./index.php?type=logout">Log out</a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>



		<div class="container">

		  	<div class="row fill">


			  	<div class="col-md-12">
			  		
			  		<div class="col-md-8 chatbox">

						<div class="row">	
							<div class="col-md-12 outer-chatbox">
							<h3>Chat</h3><hr>
							<br>
							
								<div id="chat-content">
							  		<?php
							  			echo $chathtml;
							  		?>
							  	</div>

							</div>
						</div>

					  	<div class="row">	
					  		
						  <div class="form-group whiteback">
						    <input class="messageinput" type="text" name="msg" placeholder="Type your message here...">
						    <button type="submit" class="inputbutton btn btn-default">Send</button>
						  </div>
							
						</div>
					
				  	</div>

			  		<div class="col-md-1"></div>				  	

				  	<div class="col-md-3 friendlist">
				  		<h3>Friends List</h3><hr>
				  		<?php
				  			echo $friendshtml;
				  		?>
				  	</div>

			  	</div>	
	  				  	
			</div>
		
		  	
		</div>


	<div class="footer navbar-fixed-bottom">
      <div class="container footer-container">
      	<span style="display: none;" id="lastupdated"></span>
        <p>Place sticky footer content here.</p>
      </div>
    </div>
		
	</body>

</html>