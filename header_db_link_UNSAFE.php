<?php
//$db_host already set
$row = mysqli_fetch_assoc(mysqli_query($link_root, "SELECT db, db_user, db_pass, name, email, phone, email_sms FROM doctors WHERE username = '"."mahima"."'"));
$db_user = $row['db_user'];
$db_pass = $row['db_pass'];
$db_database = $row['db'];
$dr_name = $row['name'];
$dr_email = $row['email'];
$dr_phone = $row['phone'];
$dr_email_sms = $row['email_sms'];
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_database) or die('Unable to establish DB connection');
mysqli_query($link_root, "SET names UTF8");
?>
