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
		$mail             = new PHPMailer();

		$body             = "HELLO";

		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "mail.drmahima.com"; // SMTP server
		$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "drmahimasms@gmail.com";  // GMAIL username
		$mail->Password   = "pavilion.1";            // GMAIL password

		$mail->SetFrom('drmahimasms@gmail.com', 'Apoorv Umang');

		$mail->AddReplyTo("drmahimasms@gmail.com","Apoorv Umang");

		$mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$mail->MsgHTML($body);

		$address = "apoorvumang@gmail.com";
		$mail->AddAddress($address, "Apoorv Umang");

		if(!$mail->Send()) {
		  echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		  echo "Message sent!";
		}
		    
		// $result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id = {$_GET['id']} ORDER BY date, v_id");
		// $subject = "Vaccination schedule for ".$patient['name'];
		// $message = "Dear ".$patient['name']."<br><br>Please find below your vaccination schedule";
		// $message .= "<br><br><br>";
		// $message .= "<table><tr><th>Date</th><th>Vaccine</th></tr>";

		// while($row = mysqli_fetch_assoc($result))
		// {
		// 	$vac = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE id = {$row['v_id']}"));
		// 	if($row['given'] == 'N' && (strtotime("now") < strtotime($row['date'])))
		// 		$message =  $message."<tr><td>".date('j M Y',strtotime($row['date']))."</td><td>".$vac['name']."</td></tr>";
		// }
		// $message = $message."</table><br>";
		// $message .= "Regards<br>Dr. Mahima";

		// $headers = 'MIME-Version: 1.0' . "\r\n";
		// $headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
		// $headers .= "From: Dr. Mahima <mahima@drmahima.com>\r\n";
		// if(mail($patient['email'], $subject, $message, $headers)) {
		// 	echo 'success';
		// }
		// if($patient['email2'])
		// 	mail($patient['email2'], $subject, $message, $headers);	
	}
	else
	{
		$pdf = createPrintSchedulePDF($patient['id'], $link);

	    $email = new PHPMailer();
		$email->From      = 'mahima@drmahima.com';
		$email->FromName  = 'Dr. Mahima';
		$email->Subject   = 'Vaccination history';
		$email->Body      = "Dear ".$patient['name']."\n\nPlease find attached your vaccination history\n\nRegards\nDr.Mahima";
		$message = $email->Body;
		$email->AddAddress( $patient['email'] );
		if($patient['email2'])
			$email->AddAddress($patient['email2']);

		$email->AddStringAttachment($pdf->Output('', 'S'), 'history.pdf', "base64", 'application/pdf');

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