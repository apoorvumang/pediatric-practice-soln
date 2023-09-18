<?php
include_once('header.php');
require_once 'PHPMailer/PHPMailerAutoload.php';

function PHPMailerWithSMTP() {
    global $dr_email, $dr_name, $dr_email_pass_onecom;
    $email = new PHPMailer();
    $email->isHTML(true); 
    $email->isSMTP();                                            //Send using SMTP
    $email->Host       = 'send.one.com';                     //Set the SMTP server to send through
    $email->SMTPAuth   = true;                                   //Enable SMTP authentication
    $email->Username   = $dr_email;                     //SMTP username
    $email->Password = $dr_email_pass_onecom;
    // $email->SMTPAutoTLS = true; 							// IMPORTANT!!
    $email->SMTPSecure = 'tls';
    $email->Port       = 2525;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $email->setFrom($dr_email, $dr_name);
    return $email;
}



// $email = PHPMailerWithSMTP();


// $email->SMTPDebug = 3;

// $email->Subject = 'Test email number 2';
// $email->Body = 'This is a second test email from PHPMailer with SMTP support';

// $email->AddAddress( 'apoorvumang@gmail.com' );
// echo "hello";
// if($email->Send()) {
//     echo 'Email sent successfully';
// }
// else {
//     echo 'Error in sending email';
// }

?>
