<?php

include './config.php';

if ($_GET['type'] == 'logout') {
	setcookie("auth", "", time() - 3600);
}

if (isset($_COOKIE['auth'])) {
	header("Location: " . $domain . "/chat.php"); /* Redirect browser */
	exit();
	
} else {
	if ($_POST['try'] == 'true') {
		if ($_POST['mob'] != '' && $_POST['pin'] != '') {

			$url = $domain . "/auth.php?mob=" . $_POST['mob'] . "&pin=" . $_POST['pin'];
			$result = file_get_contents($url);

			if ($result == 'true') {

				$cookie_name = "auth";
				$cookie_value = $_POST['mob'];
				setcookie($cookie_name, $cookie_value, time() + (10 * 365 * 24 * 60 * 60), "/"); 
				header("Location: " . $domain . "/chat.php"); /* Redirect browser */
				exit();
			} else {
				$error = 'The Mobile number and PIN do not match our records. Please try again.';
			}

		} else {
			if ($_POST['mob'] == '') {
				$error = 'Please provide a Mobile number.';
			} else {
				$error = 'Please provide a PIN number.';
			}
		}
	}
}


?>



<!DOCTYPE html>
<html>

	<head>
		<title>Project Arcturus - Login</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="./styles.css">

	</head>

	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <a class="navbar-brand" href="#">Project Arcturus - Chat App</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav navbar-right">
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>

		<div style="margin-top: 50px;" class="container">

		  	<div class="row fill">

			  	<div class="col-md-12 col-sm-12">
			  		<h2>Login Page</h2><br>
			  	</div>

				<div class="col-md-6 col-sm-12">
					<form method="post" action="index.php">
					  <div class="form-group">
					    <label>Mobile No.</label><br>
					    <input class="textinput" type="text" class="form-control" value="<?php echo $_POST['mob']; ?>" name="mob" placeholder="e.g. 0412 345 678">
					  </div>
					  <div class="form-group">
					    <label>PIN (4 numbers)</label><br>
					    <input class="textinput" type="password" class="form-control" name="pin" placeholder="e.g 1234">
					  </div>
					  <input type="hidden" name="try" value="true">
					  <button class="inputbutton" type="submit" class="btn btn-default">Sign in</button>
					</form>	  	
					<br><p class="error">
						<?php echo $error; ?>
					</p>
				</div>

			</div>
		  	
		</div>


		
	</body>

</html>