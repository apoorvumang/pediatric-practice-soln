<?php
require('connect.php');
include('header_db_link.php');

include_once('fpdf/fpdf.php');


session_name('tzLogin');
session_start();
error_reporting(0);


if($_GET['pdf_id']) {
  $query = "SELECT * FROM medcerts WHERE id={$_GET['pdf_id']};";
  $row = mysqli_fetch_assoc(mysqli_query($link, $query));
  $decoded_pdf = base64_decode($row['pdf']);
  header("Content-type:application/pdf");
  echo $decoded_pdf;
}

?>
