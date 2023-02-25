<?php
include_once('header.php');
require_once 'PHPMailer/PHPMailerAutoload.php';

function PHPMailerWithSMTP() {
    $email = new PHPMailer();
    $email->isHTML(true); 
    $email->isSMTP();                                            //Send using SMTP
    $email->Host       = 'send.one.com';                     //Set the SMTP server to send through
    $email->SMTPAuth   = true;                                   //Enable SMTP authentication
    $email->Username   = 'mahima@drmahima.com';                     //SMTP username
    $email->Password   = 'vultr123';                               //SMTP password
    $email->SMTPAutoTLS = true; 							// IMPORTANT!!
    $email->Port       = 25;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $email->setFrom('mahima@drmahima.com', 'Dr. Mahima');
    return $email;
}

?>
