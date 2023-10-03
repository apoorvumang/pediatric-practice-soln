<?php
require('connect.php');
include('header_db_link.php');

$p_id = $_POST['p_id'];

// make a query to get the patient's details
$query = "SELECT * from patients WHERE id={$p_id}";
$result = mysqli_query($link, $query);

// check if the query was successful
if ($result) {
  $patient = mysqli_fetch_assoc($result);
  // return the patient's details as a JSON object
  echo json_encode($patient);
} else {
  // handle the error
  echo json_encode(['error' => 'Could not fetch patient details']);
}

?>
