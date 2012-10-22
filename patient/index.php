<?php


//session_name('tzLogin');
//require 'connect.php';
include('header.php');
// Those two files can be included only if INCLUDE_CHECK is defined
//session_start();
//session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks

?>
<script>
$(function() {
	$( "#dob_show" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy",
		altField: "#dob",
		altFormat: "yy-mm-dd"
	});
});
</script>
<?php

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
	
	
	if(!$_POST['id'] || !$_POST['dob'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		// Escaping all input data
		$_POST['id'] = mysqli_real_escape_string($link, $_POST['id']);
		$_POST['dob'] = mysqli_real_escape_string($link, $_POST['dob']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT id,dob FROM patients WHERE id='{$_POST['id']}' AND dob='{$_POST['dob']}'"));

		if($row['id'])
		{
			// If everything is OK login
			
			$_SESSION['id'] = $row['id'];
			$_SESSION['name'] = $row['name'];
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

	echo "<h3>Welcome parents of {$_SESSION['name']}!</h3><br />";
	?>
<p><strong>Use the above links to navigate.</strong></p>
<?php
	include('footer.php');
	exit;
}

?>
			<form class="clearfix" action="" method="post">
					<h3>Patient Login</h3>
					<p>
					<label class="grey" for="id">Child ID:</label><br />
					<input class="field" type="text" name="id" id="id" value="" size="23" />
					</p>
					<p>
					<label class="grey" for="dob">Date of Birth:</label><br />
					<input type="text" name="dob_show" id="dob_show" size="23" />
					<input type="hidden" name="dob" id="dob" size="23" />
					</p>
					<p>
					
        			<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="0" /> &nbsp;Remember me</label>
	            	</p>
	            	<p>
					<input type="submit" name="submit" value="Login" class="bt_login" />
					</p>
				
			</form>
<?php include('footer.php');?>