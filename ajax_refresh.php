<?php
$arrlength = count($_POST);
$result =  mysqli_query($link, "SELECT name, id FROM patients WHERE name LIKE {$_POST['keyword']}");
$var = 0;
while($row = mysqli_fetch_assoc($result))
{
	$var++;
	echo "<li>".$row['name']."</li>";
}
echo $var;
// foreach($_POST as $x => $x_value) {
//     echo "Key=" . $x . ", Value=" . $x_value;
//     echo "<br>";
// }
?>