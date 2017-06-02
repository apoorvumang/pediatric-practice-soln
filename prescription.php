<?php
require('connect.php');
include('header_db_link.php');

if($_POST['visit_id']) {
  $visit_id = $_POST['visit_id'];
  $image_url = $_POST['image_url'];
  $query = "UPDATE notes SET image_url='{$image_url}' WHERE id={$visit_id};";
  if(mysqli_query($link, $query)) {
    echo 'Succesfully added prescription!';
  } elso {
    echo 'Unable to add prescription!';
  }
}
?>
