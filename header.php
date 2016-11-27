<?php
define('INCLUDE_CHECK',true);
require 'connect.php';
include('header_db_link.php');

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

if(isset($_GET['logout']))
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
<!--- basic page needs
   ================================================== -->
   <meta charset="utf-8">

   <!-- mobile specific metas
   ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

 	<!-- CSS
   ================================================== -->
   <link rel="stylesheet" href="css/base.css">
   <link rel="stylesheet" href="css/vendor.css">  
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="css/mycss.css">     

   <!-- script
   ================================================== -->
	<script src="js/modernizr.js"></script>
	<script src="js/pace.min.js"></script>

   <!-- favicons
	================================================== -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
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
	if($_SESSION['name'])
	{
		$title = $title." - ".$_SESSION['name'];
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
	case "edit-sched.php":
	$title = "Edit vaccination schedule";
	break;
	case "changevac.php":
	$title = "Edit/Delete Vaccine";
	break;
	case "addvac.php":
	$title = "Add/Edit Vaccine";
	break;
  case "visits-today.php":
	$title = "Visits";
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
	case "search-scheddg.php":
	$title="Search schedule by given date";
	break;
	case "search-sched-resultsdg.php":
	$title="Search schedule by given date - Results";
	break;
	case "search-patient.php":
	$title="Search patients";
	break;
	case "search-patient-results.php":
	$title="Search patients - Results";
	break;
	case "email.php":
	$title="Patient - Send E-Mail";
	break;
	case "visits.php":
	$title="Visit log";
	break;
	case "visits-results.php":
	$title="Visit log - results";
	break;
	case "addvisit.php":
	$title="Add visit";
	break;
	case "payment_due.php":
	$title="Payments Due";
	break;
	case "visits.php":
	$title = "Add patient visit";
	break;
	case "settings.php":
	$title = "Settings";
	break;
}

echo $title;

//Switch case for Title in header.php

?>
</title>

<script type="text/javascript" src="jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.simplePagination.js"></script>
<!-- <script src="http://malsup.github.com/jquery.form.js"></script> -->

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

//Note: This just counts the number of checkboxes on that are ticked
//on the page and gets attribute phoneCount. Don't know what will happen
//if phoneCount attribute is not present. Maybe an error? Need to test it.
function countMessages(e)
{
	var i, len, inputs = document.getElementsByTagName("input");
	var checked_num = 0;
	var patientIDList = [];
    for (i = 0, len = inputs.length; i < len; i++) {
        if (inputs[i].type === "checkbox" && inputs[i].checked)
        {
        	if($.inArray(inputs[i].getAttribute("patientID"), patientIDList) == -1)
        		patientIDList.push(inputs[i].getAttribute("patientID"));
        	else
        		continue;
        	if(inputs[i].getAttribute("phoneCount") == 2)
        		checked_num += 2;
        	else if(inputs[i].getAttribute("phoneCount") == 1)
        		checked_num += 1;
        }
    }
   if(!confirm('Send ' + checked_num + ' messages?'))e.preventDefault();
};

</script>
</head>
<body id="top">

<header class="short-header">   

   	<div class="gradient-block"></div>	

   	<div class="row header-content">

   		<div class="logo">
	        <a href="index.php">Author</a>
	      </div>

	   	<nav id="main-nav-wrap">
				<ul class="main-navigation sf-menu">
					<li <?php if($currentFile=="index.php") { ?> class="current" <?php }?>><a href="index.php">Home</a></li>
					<?php if($_SESSION['name']) { ?>
					<li <?php if($currentFile=="register.php") { ?> class="current" <?php }?>><a href="register.php">New patient</a></li>
					<li <?php if($currentFile=="search-patient.php") { ?> class="current" <?php }?>><a href="search-patient.php">Patient info</a></li>
					<?php } ?>
					<?php if($_SESSION['type']=='employee') { ?>
					<li <?php if($currentFile=="addvisit.php") { ?> class="current" <?php }?>><a href="addvisit.php">Add Patient Visit</a></li>
					<?php } ?>
		      <li <?php if($currentFile=="visits-today.php") { ?> class="current" <?php }?>><a href="visits-today.php">Visits</a></li>
					<?php if($_SESSION['type']=='doctor') { ?>
					<li <?php if($currentFile=="search-sched.php") { ?> class="current" <?php }?>><a href="search-sched.php">Appointment Search</a></li>
					<li <?php if($currentFile=="search-scheddg.php") { ?> class="current" <?php }?>><a href="search-scheddg.php">Given Search</a></li>
					<li <?php if($currentFile=="payment_due.php") { ?> class="current" <?php }?>><a href="payment_due.php">Payment Due</a></li>
					<li <?php if($currentFile=="settings.php") { ?> class="current" <?php }?>><a href="settings.php">Settings</a></li>
					<?php } ?>
					<?php if($_SESSION['name']) { ?>
					<li><a href="index.php?logout=1">Logout</a></li>
					<?php } ?>
				</ul>
			</nav> <!-- end main-nav-wrap -->

			

			<div class="triggers">
				<a class="menu-toggle" href="#"><span>Menu</span></a>
			</div> <!-- end triggers -->	
   		
   	</div>     		
   	
   </header> <!-- end header -->


<!-- content-outer -->
<div id="content-wrap" class="styles" >

	
	   <?php if((!$_SESSION['name'])&&($currentFile!="index.php")) {

	   	echo "<h2>Access Denied!</h2>";
	   	exit;
	   } ?>
