<?php include('header.php');
include_once('fpdf/fpdf.php');
include_once('patient/pdf-functions.php');
require 'PHPMailer/PHPMailerAutoload.php';
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
		$message = "Dear ".$patient['name']."<br><br>Please find below your vaccination schedule";
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
		if(mail($patient['email'], $subject, $message, $headers)) {
			echo 'success';
		}
		if($patient['email2'])
			mail($patient['email2'], $subject, $message, $headers);	
	}
	else
	{
		$pdf = createPrintSchedulePDF($patient['id'], $link);

	    $email = new PHPMailer();
		$email->From      = 'mahima@drmahima.com';
		$email->FromName  = 'Dr. Mahima';
		$email->Subject   = 'Test';
		$email->Body      = 'Hello';
		$email->AddAddress( $patient['email'] );

		$email->AddStringAttachment($pdf->Output('', 'S'), 'message.pdf', "base64", 'application/pdf');

		if($email->Send()) {
			echo 'Email sent successfully';
		}
		else {
			echo 'Error in sending email';
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