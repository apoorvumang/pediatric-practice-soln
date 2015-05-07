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

	// $result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id = {$_GET['id']} ORDER BY date, v_id");

	// $subject = "Vaccination schedule for ".$patient['name'];
	// $message = "Dear Parent<br><br>Please find below the vaccination schedule for your child ".$patient['name'];
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
	// mail($patient['email'], $subject, $message, $headers);
?>

<?php
//define the receiver of the email
$to = 'apoorvumang@gmail.com';
//define the subject of the email
$subject = 'Test email with attachment';
//create a boundary string. It must be unique
//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));
//define the headers we want passed. Note that they are separated with \r\n
$headers .= "From: Dr. Mahima <mahima@drmahima.com>\r\n";
//add boundary string and mime type specification
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
//read the atachment file contents into a string,
//encode it with MIME base64,
//and split it into smaller chunks
$attachment = chunk_split(base64_encode(file_get_contents("http://www.drmahima.com/mummy-software/patient/pdf.php?id=69")));
//define the body of the message.
ob_start(); //Turn on output buffering
?>
--PHP-mixed-<?php echo $random_hash; ?>
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"

--PHP-alt-<?php echo $random_hash; ?>
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

Hello World!!!
This is simple text email message.

--PHP-alt-<?php echo $random_hash; ?>
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<h2>Hello World!</h2>
<p>This is something with <b>HTML</b> formatting.</p>

--PHP-alt-<?php echo $random_hash; ?>--

--PHP-mixed-<?php echo $random_hash; ?>
Content-Type: application/zip; name="attachment.zip"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

<?php echo $attachment; ?>
--PHP-mixed-<?php echo $random_hash; ?>--

<?php
//copy current buffer contents into $message variable and delete current output buffer
$message = ob_get_clean();
//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed"
echo $mail_sent ? "Mail sent" : "Mail failed";
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