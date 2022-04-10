<?php
session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);
session_start();
error_reporting(0);

if(isset($_SESSION['username'])) {
	//$db_host already set
	$row = mysqli_fetch_assoc(mysqli_query($link_root, "SELECT db, db_user, db_pass, name, email, phone, email_sms FROM doctors WHERE username = '".$_SESSION['username']."'"));
	$db_user = $row['db_user'];
	$db_pass = $row['db_pass'];
	$db_database = $row['db'];
	$dr_name = $row['name'];
	$dr_email = $row['email'];
	$dr_phone = $row['phone'];
	$dr_email_sms = $row['email_sms'];
	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_database) or die('Unable to establish DB connection');
	
	mysqli_query($link_root, "SET names UTF8");
}
?>