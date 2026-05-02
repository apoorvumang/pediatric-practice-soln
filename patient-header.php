<?php
/**
 * Patient Portal Header
 * Handles patient session management and DB connection.
 * This is the patient equivalent of header.php + header_db_link.php.
 */

session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);

require_once 'connect.php';
$patient_cookie_secret = hash_hmac('sha256', 'patient-portal', $password . $servername);

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

// Cookie-based auto-login when session has expired
if (!$patient_logged_in && !empty($_COOKIE['tzPatientRemember'])) {
    $parts = explode('|', $_COOKIE['tzPatientRemember'], 4);
    if (count($parts) === 4) {
        list($ck_pid, $ck_dob, $ck_exp, $ck_mac) = $parts;
        $expected = hash_hmac('sha256', $ck_pid . '|' . $ck_dob . '|' . $ck_exp, $patient_cookie_secret);
        if (time() <= (int)$ck_exp && hash_equals($expected, $ck_mac)) {
            $ck_pid_int = (int)$ck_pid;
            $dr_rows = mysqli_query($link_root, "SELECT db, db_user, db_pass FROM doctors");
            while ($dr = mysqli_fetch_assoc($dr_rows)) {
                if (!$dr['db'] || !$dr['db_user']) continue;
                $tl = @mysqli_connect($db_host, $dr['db_user'], $dr['db_pass'], $dr['db']);
                if (!$tl) continue;
                $st = mysqli_prepare($tl, "SELECT id, name FROM patients WHERE id = ? AND dob = ?");
                if ($st) {
                    mysqli_stmt_bind_param($st, "is", $ck_pid_int, $ck_dob);
                    mysqli_stmt_execute($st);
                    $ck_patient = mysqli_fetch_assoc(mysqli_stmt_get_result($st));
                    mysqli_stmt_close($st);
                    if ($ck_patient) {
                        session_regenerate_id(true);
                        $_SESSION['patient_id']      = (int)$ck_patient['id'];
                        $_SESSION['patient_name']    = $ck_patient['name'];
                        $_SESSION['patient_db']      = $dr['db'];
                        $_SESSION['patient_db_user'] = $dr['db_user'];
                        $_SESSION['patient_db_pass'] = $dr['db_pass'];
                        $link = $tl;
                        mysqli_query($link, "SET names UTF8");
                        $patient_logged_in = true;
                        break;
                    }
                }
                mysqli_close($tl);
            }
        }
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['patient_id']);
    unset($_SESSION['patient_name']);
    unset($_SESSION['patient_db']);
    unset($_SESSION['patient_db_user']);
    unset($_SESSION['patient_db_pass']);
    setcookie('tzPatientRemember', '', time() - 3600, '/', '', false, true);
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
