<?php
define('INCLUDE_CHECK',true);
require 'connect.php';
session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);
session_start();
error_reporting(0);
if(isset($_GET['logout']))
{
	$_SESSION = array();
	session_destroy();
	
	Redirect("index.php");
	exit;
}

function Redirect($Str_Location, $Bln_Replace = 1, $Int_HRC = NULL)
{
        if(!headers_sent())
        {
            header('location: ' . urldecode($Str_Location), $Bln_Replace, $Int_HRC);
            exit;
        }

    exit('<meta http-equiv="refresh" content="0; url=' . urldecode($Str_Location) . '"/>'); # | exit('<script>document.location.href=' . urldecode($Str_Location) . ';</script>');
    return;
}

?>
<!DOCTYPE html>
<head>
<meta charset="utf8" />
 
<title>
<?php
$title = "Access Denied";
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];
switch($currentFile)
{
	case "index.php": 
	$title = "Home";
	if($_SESSION['id'])
	{
		$title = $title." - ".$_SESSION['username'];
	}
	else
	{
		$title = $title." - Login";
	}
	break;
	case "register.php":
	$title = "Register New Patient";
	break;
	case "vaccine.php":
	$title = "Add New Vaccine";
	break;
	case "schedule.php":
	$title = "Schedule vaccination for patients";
	break;
	case "show.php":
	$title = "Patient Information";
	break;
	case "edit-sched.php":
	$title = "Edit vaccination schedule";
	break;
	case "changevac.php":
	$title = "Edit/Delete Vaccine";
	break;
	case "addvac.php":
	$title = "Add/Edit Vaccine";
	break;
	case "addvacmake.php":
	$title = "Add product";
	break;
	case "changevacmake.php":
	$title = "Change product";
	break;
	case "editpatient.php":
	$title="Edit patient";
	break;
	case "search-sched.php":
	$title="Search schedule";
	break;
	case "search-sched-results.php":
	$title="Search schedule - Results";
	break;
}

echo $title;
	
//Switch case for Title in header.php

?>
</title>

<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />

<!-- datepicker things from jqueryui -->
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>

</head>
<body>
<!--header -->
<div id="header-wrap"><div id="header">

	<a name="top"></a>

	<h1 id="logo-text"><a href="index.php" title="">Pediatric Software</a></h1>
	<p id="slogan">Schedules vaccines for patients</p>

	<div  id="nav">
		<ul>
			<li <?php if($currentFile=="index.php") { ?> id="current" <?php }?>><a href="index.php">Home</a></li>
			<?php if($_SESSION['name']) { ?>
			<li <?php if($currentFile=="register.php") { ?> id="current" <?php }?>><a href="register.php">Add patient</a></li>
			<li <?php if($currentFile=="vaccine.php") { ?> id="current" <?php }?>><a href="vaccine.php">Add Vac</a></li>
			<li <?php if($currentFile=="changevac.php") { ?> id="current" <?php }?>><a href="changevac.php">Edit/Del Vac</a></li>
			<li <?php if($currentFile=="schedule.php") { ?> id="current" <?php }?>><a href="schedule.php">Sched Vac</a></li>
			<li <?php if($currentFile=="show.php") { ?> id="current" <?php }?>><a href="show.php">Patient info</a></li>
			<li <?php if($currentFile=="search-sched.php") { ?> id="current" <?php }?>><a href="search-sched.php">Search Schedule</a></li>
			<li <?php if($currentFile=="addvacmake.php") { ?> id="current" <?php }?>><a href="addvacmake.php">Add product</a></li>
			<li <?php if($currentFile=="changevacmake.php") { ?> id="current" <?php }?>><a href="changevacmake.php">Change product</a></li>
			<li><a href="index.php?logout=1">Logout</a></li>
			<?php } ?>
		</ul>
	</div>

<!--/header-->
</div></div>

<!-- content-outer -->
<div id="content-wrap" class="clear" >

	<!-- content -->
   <div id="content">

   	<!-- main -->
	   <div id="main">