<?php
define('INCLUDE_CHECK',true);
require '../connect.php';
session_name('tzLogin');
session_start();
error_reporting(0);

function Redirect($Str_Location, $Bln_Replace = 1, $Int_HRC = NULL)
{
	//header function does not seem to be working on website (drmahima.com)
        // if(!headers_sent())
        // {
        //     header('location: ' . urldecode($Str_Location), $Bln_Replace, $Int_HRC);
        //     exit;
        // }

    exit('<meta http-equiv="refresh" content="0; url=' . urldecode($Str_Location) . '"/>'); # | exit('<script>document.location.href=' . urldecode($Str_Location) . ';</script>');
    return;
}

if(isset($_GET['logout'])||isset($_SESSION['username']))	//username means doctor logged in
{
	$_SESSION = array();
	session_destroy();
	
	Redirect("index.php");
	exit;
}

?>
<!DOCTYPE html>
<link rel="shortcut icon" href="http://www.drmahima.com/favicon.ico" />
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
		$title = $title." - ".$_SESSION['name'];
	}
	else
	{
		$title = $title." - Login";
	}
	break;

	case "myinfo.php":
	$title = "My Info - ".$_SESSION['name'];
	break;
}

echo $title;
	
//Switch case for Title in header.php

?>
</title>

<link rel="stylesheet" type="text/css" media="screen" href="../css/screen.css" />
<!-- datepicker things from jqueryui -->
<link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript">

function uncheckAll()
{
	var array = document.getElementsByTagName("input");
	for(var ii = 0; ii < array.length; ii++)
	{
	   if(array[ii].type == "checkbox")
	   {
	        array[ii].checked = false;
	   }
	}
};

function checkAll()
{
	var array = document.getElementsByTagName("input");
	for(var ii = 0; ii < array.length; ii++)
	{
	   if(array[ii].type == "checkbox")
	   {
	        array[ii].checked = true;
	   }
	}
};

</script>
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
			<li <?php if($currentFile=="myinfo.php") { ?> id="current" <?php }?>><a href="myinfo.php">My Info</a></li>
			<li><a href=<?php echo "\""."pdf.php?id=".$_SESSION['id']."\"" ?>>View Vaccine Schedule</a></li>
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
	   <?php
	   if((!$_SESSION['name'])&&($currentFile!="index.php")) {

	   	echo "<h2>Access Denied!</h2>";
	   	exit;
	   } ?>