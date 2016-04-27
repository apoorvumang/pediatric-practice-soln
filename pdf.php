<?php
require('connect.php');
include('header_db_link.php');

include_once('fpdf/fpdf.php');
require('pdf-functions.php');


session_name('tzLogin');
session_start();
error_reporting(0);

if((!isset($_GET['id']))||(!(isset($_SESSION['id'])||isset($_SESSION['username']))))
{
	echo '<h2>Access Denied</h2>';
	exit;
}
$pdf = createPrintSchedulePDF($_GET['id'], $link);
$pdf->Output();
?>