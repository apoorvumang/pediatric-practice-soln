<?php
require('pdf-functions.php');
session_name('tzLogin');
session_start();
error_reporting(0);

if((!isset($_GET['id']))||(!(isset($_SESSION['id'])||isset($_SESSION['username']))))
{
	echo '<h2>Access Denied</h2>';
	exit;
}
if(isset($_SESSION['id']))
	$_GET['id'] = $_SESSION['id'];
$pdf = createPrintSchedulePDF($_GET['id'], $link);
$pdf->Output();
?>