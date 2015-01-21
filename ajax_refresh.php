<?php
require('connect.php');
$result =  mysqli_query($link, "SELECT name, id FROM patients WHERE name LIKE \"%".$_POST["keyword"]."%\" AND id NOT IN
	(SELECT s_id FROM siblings WHERE p_id = {$_POST['myid']})");
//autocomplete but dont choose self or those who are already siblings
while($row = mysqli_fetch_assoc($result))
{
	if($_POST['myid'] != $row['id'])
		echo "<li onclick=\"set_item('".$row['id']."');\">".$row['name']."</li>";
}
// echo $var;
// echo "SELECT name, id FROM patients WHERE name LIKE \"%".$_POST["keyword"]."%\"";
// foreach($_POST as $x => $x_value) {
//     echo "Key=" . $x . ", Value=" . $x_value;
//     echo "<br>";
// }
?>