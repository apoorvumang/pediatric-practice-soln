<?php

//if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');


/* Database config */

$db_host		= 'localhost';//'mysql.iakshay.net'
$db_user		= 'root';//'apoorv';
$db_pass		= '';//'patient';
$db_database	= 'mummy_software';//'apoorv_heroku';

/* End config */

$link = mysqli_connect($db_host,$db_user,$db_pass,$db_database) or die('Unable to establish a DB connection');

mysqli_query($link, "SET names UTF8");

?>
