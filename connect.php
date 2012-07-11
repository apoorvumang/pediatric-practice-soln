<?php

//if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');


/* Database config */

$db_host		= 'localhost';
$db_user		= 'root';//'a8151hos_akshay';
$db_pass		= '';//'P$m,;EO^XAsD';
$db_database	= 'mummy_software';//'a8151hos_w3c'; 

/* End config */

$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_select_db($db_database,$link);
mysql_query("SET names UTF8");

?>
