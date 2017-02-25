<?php
require('connect.php');
include('header_db_link.php');
include('header.php');

session_name('tzLogin');
session_start();
error_reporting(0);

if((!isset($_GET['id']))||(!(isset($_SESSION['id'])||isset($_SESSION['username']))))
{
	echo '<h2>Access Denied</h2>';
	exit;
}
?>
<h3>Create Invoice for someone</h3>
<?php
include('footer.php');
?>
