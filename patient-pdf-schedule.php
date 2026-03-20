<?php
/**
 * Patient-accessible vaccination schedule PDF viewer.
 * Only serves the schedule for the logged-in patient.
 */
session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);
session_start();
error_reporting(0);

require 'connect.php';

// Verify patient is logged in
if (!isset($_SESSION['patient_id']) || !isset($_SESSION['patient_db'])) {
    echo '<h2>Access Denied</h2>';
    exit;
}

$link = mysqli_connect($db_host, $_SESSION['patient_db_user'], $_SESSION['patient_db_pass'], $_SESSION['patient_db']);
if (!$link) {
    echo '<h2>Database Error</h2>';
    exit;
}

$patient_id = (int)$_SESSION['patient_id'];
$requested_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Only allow viewing own schedule
if ($requested_id !== $patient_id) {
    echo '<h2>Access Denied - You can only view your own schedule.</h2>';
    exit;
}

include_once('fpdf/fpdf.php');
require('pdf-functions.php');

$pdf = createPrintSchedulePDF($patient_id, $link);
$pdf->Output();

mysqli_close($link);
?>
