<?php
require('connect.php');
include('header_db_link.php');

include_once('fpdf/fpdf.php');
require('pdf-functions-medcert.php');


session_name('tzLogin');
session_start();
error_reporting(0);

// if((!isset($_POST['patient_name']))||(!(isset($_SESSION['id'])||isset($_SESSION['username']))))
// {
// 	echo '<h2>Access Denied</h2>';
// 	exit;
// }



$pdf = createMedCertPDF($_POST, $link);
$pdf_base64 = base64_encode($pdf->Output("doc.pdf", "S"));
if($_POST['save_pdf'] == "true") {
  $query = "INSERT INTO medcerts(p_id, pdf) VALUES({$_POST['p_id']}, '{$pdf_base64}');";
  $result = mysqli_query($link, $query);
}
$decoded_pdf = base64_decode($pdf_base64);
header("Content-type:application/pdf");
echo $decoded_pdf;


?>
