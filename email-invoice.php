<?php include('header.php');
include_once('fpdf/fpdf.php');
include_once('pdf-functions-invoice.php');
require_once 'PHPMailer/PHPMailerAutoload.php';
include_once('email-smtp-auth.php');
// var_dump($_POST);
// exit;
if($_POST['invoice_number'])
{
  $invoice_numbers = $_POST['invoice_number'];
  $patient_id = $_POST['p_id'];
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$patient_id};"));

	if(!$patient)
	{
		echo "<h3>No patient with given ID found</h3>";
		include("footer.php");
		exit;
	}




	$email = PHPMailerWithSMTP();
	$email->From      = $dr_email;
	$email->FromName  = $dr_name;
	$email->Subject   = 'Invoices - Dr. Mahima';
	$email->Body      = "Dear ".$patient['name']."\n\nPlease find attached your invoices.\n\nRegards\n".$dr_name;
	$message = $email->Body;
	$email->AddAddress( $patient['email'] );
	if($patient['email2'])
		$email->AddAddress($patient['email2']);

  foreach ($invoice_numbers as $key => $i_no) {
    $pdf = createInvoicePDF($i_no, $link);
    $email->AddStringAttachment($pdf->Output('', 'S'), "invoice-{$i_no}.pdf", "base64", 'application/pdf');
  }



	if($email->Send()) {
		echo 'Email sent successfully';
	}
	else {
		echo 'Error in sending email';
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
