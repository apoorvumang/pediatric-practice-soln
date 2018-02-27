<?php
require('connect.php');
include('header_db_link.php');

include_once('fpdf/fpdf.php');
require('pdf-functions-medcert_with_fitness_and_vac.php');


session_name('tzLogin');
session_start();
error_reporting(0);

// if((!isset($_POST['patient_name']))||(!(isset($_SESSION['id'])||isset($_SESSION['username']))))
// {
// 	echo '<h2>Access Denied</h2>';
// 	exit;
// }
$pdf = createMedCertWithFitnessAndVacPDF($_POST, $link);
$pdf->Output();
?>
