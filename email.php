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

	$subject = "Vaccination schedule for ".$patient['name'];
	$message = "Dear Parent<br><br>Please find below the vaccination schedule for your child ".$patient['name'];
	$message .= "<br><br><br>";
	$message .= "<table><tr><th>Date</th><th>Vaccine</th></tr>";
	
	while($row = mysqli_fetch_assoc($result))
	{
		$vac = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE id = {$row['v_id']}"));
		if($row['given'] == 'N' && (strtotime("now") < strtotime($row['date'])))
			$message =  $message."<tr><td>".date('j M Y',strtotime($row['date']))."</td><td>".$vac['name']."</td></tr>";
	}
	$message = $message."</table><br>";
	$message .= "Regards<br>Dr. Mahima";

	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
	$headers .= "From: Dr. Mahima <mahima@drmahima.com>\r\n";
	mail($patient['email'], $subject, $message, $headers);
?>
Sent mail!
Mail content: <br><br>
<?php echo $message; ?>
<?php
}
else
{
	Redirect("search-patient.php");
	exit;
}
include('footer.php'); ?>