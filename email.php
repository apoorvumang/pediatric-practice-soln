<?php include('header.php'); 
if($_GET['id'])
{
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$_GET['id']}"));
	if(!$patient)
	{
		echo "<h3>No patient with given ID found</h3>";
		include("footer.php");
		exit;
	}
	$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id = {$_GET['id']} ORDER BY date, v_id");
	$count = 0;
?>
<table>
<tr>
	<th>Date</th>
	<th>Vaccine</th>
</tr>
<?php
	$message = "<table><tr><th>Date</th><th>Vaccine</th></tr>";
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
	$headers .= "From: Dr. Mahima <mahima@drmahima.com>\r\n";
	while($row = mysqli_fetch_assoc($result))
	{
		$message =  $message."<tr><td>1</td><td>2</td></tr>";
	}
	$message = $message."</table>";
	mail($patient['email'], 'Vaccination Schedule - Dr. Mahima', $message, $headers);
?>
</table>
<?php
}
else
{
	Redirect("search-patient.php");
	exit;
}
include('footer.php'); ?>