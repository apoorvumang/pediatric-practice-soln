<?php
require 'connect.php';
include('header_db_link_UNSAFE.php');

$query = "SELECT * FROM sms_pending WHERE 1";

$result = mysqli_query($link, $query);

$pending_sms = [];
while ($row = mysqli_fetch_assoc($result)) {
  $pending_sms[] = $row;
}

$pending_sms_JSON = json_encode($pending_sms);
echo $pending_sms_JSON;

?>
