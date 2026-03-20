<?php
/**
 * Patient Portal Header
 * Handles patient session management and DB connection.
 * This is the patient equivalent of header.php + header_db_link.php.
 */

session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);
session_start();
error_reporting(0);

require 'connect.php';

// Establish DB connection for patient session
$link = null;
$patient_logged_in = false;

if (isset($_SESSION['patient_id']) && isset($_SESSION['patient_db']) && isset($_SESSION['patient_db_user']) && isset($_SESSION['patient_db_pass'])) {
    $link = mysqli_connect($db_host, $_SESSION['patient_db_user'], $_SESSION['patient_db_pass'], $_SESSION['patient_db']);
    if ($link) {
        mysqli_query($link, "SET names UTF8");
        $patient_logged_in = true;
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['patient_id']);
    unset($_SESSION['patient_name']);
    unset($_SESSION['patient_db']);
    unset($_SESSION['patient_db_user']);
    unset($_SESSION['patient_db_pass']);
    // Don't destroy the whole session - doctor might be logged in too
    header('Location: patient-login.php');
    exit;
}

function patientRedirect($url) {
    exit('<meta http-equiv="refresh" content="0; url=' . urldecode($url) . '"/>');
}

// Get current file for navigation highlighting
$currentFile = basename($_SERVER["SCRIPT_NAME"]);

// Page title
$pageTitle = "Patient Portal";
if ($currentFile == "patient-portal.php") {
    $pageTitle = "Patient Portal - " . htmlspecialchars($_SESSION['patient_name'] ?? '');
} elseif ($currentFile == "patient-login.php") {
    $pageTitle = "Patient Login";
}
?>
<!DOCTYPE html>
<head>
<meta charset="utf8" />
<link rel="shortcut icon" href="http://www.drmahima.com/favicon.ico" />
<title><?php echo $pageTitle; ?></title>

<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link type="text/css" rel="stylesheet" href="css/simplePagination.css"/>
<link type="text/css" href="jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="js/jquery.easytabs.js"></script>

</head>
<body>

<!--header -->
<div id="header-wrap" style="height:60px;"><div id="header">
    <div id="nav">
        <ul>
            <?php if ($patient_logged_in) { ?>
            <li <?php if($currentFile=="patient-portal.php") { ?> id="current" <?php }?>><a href="patient-portal.php">My Dashboard</a></li>
            <li><a href="patient-header.php?logout=1">Logout</a></li>
            <?php } else { ?>
            <li><a href="index.php">Doctor Login</a></li>
            <li <?php if($currentFile=="patient-login.php") { ?> id="current" <?php }?>><a href="patient-login.php">Patient Login</a></li>
            <?php } ?>
        </ul>
    </div>
</div></div>

<!-- content-outer -->
<div id="content-wrap" class="clear">
    <!-- content -->
    <div id="content">
        <!-- main -->
        <div id="main">
        <?php if (!$patient_logged_in && $currentFile != "patient-login.php") {
            echo "<h2>Access Denied!</h2><p>Please <a href='patient-login.php'>login</a> to access the patient portal.</p>";
            exit;
        } ?>
