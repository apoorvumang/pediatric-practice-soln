<?php
require('connect.php');
include('header_db_link.php');
// $query = "UPDATE notes SET image_url='{$_POST['url']}' WHERE id={$_POST['visit_id']}";
$query = "INSERT INTO prescriptions(visit_id, url) VALUES({$_POST['visit_id']}, '{$_POST['url']}');";

$result =  mysqli_query($link, $query);

if($result) {
    echo "Added prescription image!";
} else {
    echo $query;
    echo "Error adding prescription image!";
}
?>
