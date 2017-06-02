<?php
require('connect.php');
$db_host		= 'localhost';
$db_user		= 'root';
$db_pass		= '';
$db_database	= 'drmahima_com';
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_database) or die('Unable to establish DB connection');
mysqli_query($link_root, "SET names UTF8");

$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$visit_id = $decoded['id'];
$image_url = $decoded['url'];
$query = "UPDATE notes SET image_url='{$image_url}' WHERE id={$visit_id};";
if(mysqli_query($link, $query)) {
  echo 'OK';
} else {
  echo 'NOT OK';
}

?>
