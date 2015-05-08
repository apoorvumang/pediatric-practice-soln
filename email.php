<?php include('header.php');
include_once('fpdf/fpdf.php');
include_once('patient/pdf-functions.php');
if($_GET['id'])
{
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$_GET['id']}"));
	if(!$patient)
	{
		echo "<h3>No patient with given ID found</h3>";
		include("footer.php");
		exit;
	}

	if($_GET['normal'])	//normal means upcoming schedule in normal format, not pdf attachment
	{
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
	}
	else
	{
			//define the receiver of the email
		$to = $patient['email'];
		//define the subject of the email
		$subject = 'Vaccination history (PDF attachment)';
		//create a boundary string. It must be unique
		//so we use the MD5 algorithm to generate a random hash
		$random_hash = md5(date('r', time()));
		//define the headers we want passed. Note that they are separated with \r\n
		//add boundary string and mime type specification
		//read the atachment file contents into a string,
		//encode it with MIME base64,
		//and split it into smaller chunks
		$pdf = createPrintSchedulePDF($patient['id'], $link);
		$attachment = chunk_split(base64_encode($pdf->Output('', 'S')));
		//define the body of the message.
		//
		$message = "Dear parent<br>Please find attached the vaccination history of your child ".$patient['name'];
		$message .= "<br>Regards<br>Dr. Mahima";
		    $name = "vac_hist_".$patient['name'].".pdf";
		    $header = "From: "."Dr. Mahima <mahima@drmahima.com>"."\r\n";
		    $header .= "MIME-Version: 1.0\r\n";
		    $header .= "Content-Type: multipart/mixed; boundary=\"".$random_hash."\"\r\n\r\n";
		    $header .= "This is a multi-part message in MIME format.\r\n";
		    $header .= "--".$random_hash."\r\n";
		    $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		    $header .= $message."\r\n\r\n";
		    $header .= "--".$random_hash."\r\n";
		    $header .= "Content-Type: application/octet-stream; name=\"".$name."\"\r\n"; // use different content types here
		    $header .= "Content-Transfer-Encoding: base64\r\n";
		    $header .= "Content-Disposition: attachment; filename=\"".$name."\"\r\n\r\n";
		    $header .= $attachment."\r\n\r\n";
		    $header .= "--".$random_hash."--";
		    if (mail($to, $subject, "", $header)) {
		        echo "mail send ... OK"; // or use booleans here
		    } else {
		        echo "mail send ... ERROR!";
		    }

	}



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