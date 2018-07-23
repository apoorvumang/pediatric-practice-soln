<?php
require('connect.php');
include('header_db_link.php');

$id = $_GET['id']; //prescription id
$query = "DELETE FROM prescriptions WHERE id={$id};";
$result = mysqli_query($link, $query);
if($result) {
  echo "Deleted prescription #{$id}! Load page again to see changes.";
} else {
  echo "Error in deleting prescription #{$id}!";
}

?>
