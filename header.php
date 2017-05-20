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
  case "create-invoice.php":
	$title = "Create Invoice";
	break;
	case "vaccine.php":
	$title = "Add New Vaccine";
	break;
  case "patient-vaccination-appointment-employee.php":
  $title = "Search for vaccination appointments";
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
  case "invoice-results.php":
	$title = "Invoice results";
	break;
  case "search-invoice.php":
	$title = "Search invoice";
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

<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<!-- css for simplePagination  -->
<link type="text/css" rel="stylesheet" href="css/simplePagination.css"/>
<!-- datepicker things from jqueryui -->
<link type="text/css" href="jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.simplePagination.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>

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
<body>
<!--header -->
<div id="header-wrap" style="height:60px;"><div id="header">

	<!-- <a name="top"></a>

	<h1 id="logo-text"><a href="index.php" title="">Pediatric Software</a></h1>
	<p id="slogan">Schedules vaccines for patients</p> -->

	<div  id="nav">
		<ul>
			<li <?php if($currentFile=="index.php") { ?> id="current" <?php }?>><a href="index.php">Home</a></li>
			<?php if($_SESSION['name']) { ?>
			<li <?php if($currentFile=="register.php") { ?> id="current" <?php }?>><a href="register.php">New patient</a></li>
			<li <?php if($currentFile=="search-patient.php") { ?> id="current" <?php }?>><a href="search-patient.php">Patient info</a></li>
			<?php } ?>
			<?php if($_SESSION['type']=='employee') { ?>
			<li <?php if($currentFile=="addvisit.php") { ?> id="current" <?php }?>><a href="addvisit.php">Add Patient Visit</a></li>
      <li <?php if($currentFile=="patient-vaccination-appointment-employee.php") { ?> id="current" <?php }?>><a href="patient-vaccination-appointment-employee.php">Search for vaccination appointment</a></li>
			<?php } ?>
      <li <?php if($currentFile=="visits-today.php") { ?> id="current" <?php }?>><a href="visits-today.php">Visits</a></li>
			<?php if($_SESSION['type']=='doctor') { ?>
			<li <?php if($currentFile=="search-sched.php") { ?> id="current" <?php }?>><a href="search-sched.php">Appointment Search</a></li>
			<li <?php if($currentFile=="search-scheddg.php") { ?> id="current" <?php }?>><a href="search-scheddg.php">Given Search</a></li>
			<li <?php if($currentFile=="payment_due.php") { ?> id="current" <?php }?>><a href="payment_due.php">Payment Due</a></li>
      <li <?php if($currentFile=="search-invoice.php") { ?> id="current" <?php }?>><a href="search-invoice.php">Search invoice</a></li>
			<li <?php if($currentFile=="settings.php") { ?> id="current" <?php }?>><a href="settings.php">Settings</a></li>
			<?php } ?>
			<?php if($_SESSION['name']) { ?>
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
	   <?php if((!$_SESSION['name'])&&($currentFile!="index.php")) {

	   	echo "<h2>Access Denied!</h2>";
	   	exit;
	   } ?>
