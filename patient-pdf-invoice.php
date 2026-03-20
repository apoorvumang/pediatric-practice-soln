<?php
/**
 * Patient-accessible invoice PDF viewer.
 * Verifies the invoice belongs to the logged-in patient before serving.
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
$invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$invoice_id) {
    echo '<h2>Invalid Request</h2>';
    exit;
}

// Verify this invoice belongs to the logged-in patient
$stmt = mysqli_prepare($link, "SELECT id FROM invoice WHERE id = ? AND p_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $invoice_id, $patient_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    echo '<h2>Access Denied - Invoice not found or does not belong to you.</h2>';
    exit;
}

// Invoice verified, generate PDF using existing functions
include_once('fpdf/fpdf.php');
require('pdf-functions-invoice.php');

$pdf = createInvoicePDF($invoice_id, $link);
$pdf->Output();

mysqli_close($link);
?>
