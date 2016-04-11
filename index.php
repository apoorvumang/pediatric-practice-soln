<?php


//session_name('tzLogin');
//require 'connect.php';
include('header.php');
// Those two files can be included only if INCLUDE_CHECK is defined
//session_start();
//session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks


if($_SESSION['id'] && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe'])
{
	// If you are logged in, but you don't have the tzRemember cookie (browser restart)
	// and you have not checked the rememberMe checkbox:

	$_SESSION = array();
	session_destroy();
	
	// Destroy the session
}



if(isset($_GET['logout']))
{
	$_SESSION = array();
	session_destroy();
	
	Redirect("index.php");
	exit;
}

if($_POST['submit'])
{
	// Checking whether the Login form has been submitted
	
	$err = array();
	// Will hold our errors
	
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		// Escaping all input data
		$_POST['username'] = mysqli_real_escape_string($link, $_POST['username']);
		$_POST['password'] = mysqli_real_escape_string($link, $_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM doctors WHERE username='{$_POST['username']}' AND password='".md5($_POST['password'])."'"));

		if($row['username'])
		{
			// If everything is OK login
			
			$_SESSION['username'] = $row['username'];
			$_SESSION['name'] = $row['name'];
			$_SESSION['type'] = $row['type'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			
			// Store some data in the session
			
			setcookie('tzRemember',$_POST['rememberMe']);
			Redirect("index.php");
			exit;
		}
		else $err[]='Wrong username and/or password!';
	}
	
	if($err)
	{
		echo implode('<br />',$err);
	}
}

if($_SESSION['name']){ 

	echo "<h3>Welcome {$_SESSION['name']}!</h3><br />";
	?>
<p><strong>Use the above links to navigate.</strong></p>
<?php
	include('footer.php');
	exit;
}

?>
<p>
<a href="patient/index.php">Click here for patient login</a>
</p>
			<form class="clearfix" action="" method="post">
					<h3>Doctor Login</h3>
					<p>
					<label class="grey" for="username">Username:</label><br />
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					</p>
					<p>
					<label class="grey" for="password">Password:</label><br />
					<input class="field" type="password" name="password" id="password" size="23" />
					</p>
					<p>
					
	            			<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
			            	</p>
			            	<p>
					<input type="submit" name="submit" value="Login" class="bt_login" />
					</p>
				
			</form>
<?php include('footer.php');?>
