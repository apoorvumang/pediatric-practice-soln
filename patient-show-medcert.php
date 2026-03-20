<?php
/**
 * Patient-accessible medical certificate viewer.
 * Verifies the certificate belongs to the logged-in patient before serving.
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
$pdf_id = isset($_GET['pdf_id']) ? (int)$_GET['pdf_id'] : 0;

if (!$pdf_id) {
    echo '<h2>Invalid Request</h2>';
    exit;
}

// Fetch certificate and verify it belongs to this patient
$stmt = mysqli_prepare($link, "SELECT pdf FROM medcerts WHERE id = ? AND p_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $pdf_id, $patient_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    echo '<h2>Access Denied - Certificate not found or does not belong to you.</h2>';
    exit;
}

$decoded_pdf = base64_decode($row['pdf']);
header("Content-type:application/pdf");
echo $decoded_pdf;

mysqli_close($link);
?>
