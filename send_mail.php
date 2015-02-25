<?php

    function died($error) {
 
        // your error code can go here
 
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
 
        echo "These errors appear below.<br /><br />";
 
        echo $error."<br /><br />";
 
        echo "Please go back and fix these errors.<br /><br />";
 
        die();
 
    }
 
     
 
    // validation expected data exists
 
    if(!isset($_POST['first_name']) ||
 
        !isset($_POST['last_name']) ||
 
        !isset($_POST['address']) ||
 
        !isset($_POST['message'])) {
 
        died('We are sorry, but there appears to be a problem with the form you submitted.');       
 
    }
 
     
 
    $first_name = $_POST['first_name']; // required
 
    $last_name = $_POST['last_name']; // required
	
	//$subject = $_POST['subject']; // required
 
    $email_from = $_POST['address']; // required
 
    //$telephone = $_POST['telephone']; // not required
 
    $comments = $_POST['message']; // required
 
 
	$comments = wordwrap($comments, 70, "\r\n");
     
 
    $error_message = "";
 
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
  if(!preg_match($email_exp,$email_from)) {
 
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
 
  }
 
    $string_exp = "/^[A-Za-z .'-]+$/";
 
  if(!preg_match($string_exp,$first_name)) {
 
    $error_message .= 'The First Name you entered does not appear to be valid.<br />';
 
  }
 
  if(!preg_match($string_exp,$last_name)) {
 
    $error_message .= 'The Last Name you entered does not appear to be valid.<br />';
 
  }
  /*
  if(!preg_match($string_exp,$subject)) {
 
    $error_message .= 'The Subject you entered does not appear to be valid.<br />';
 
  }
 */

  if(strlen($comments) < 2) {
 
    $error_message .= 'The Comments you entered do not appear to be valid.<br />';
 
  }
 
  if(strlen($error_message) > 0) {
 
    died($error_message);
 
  }
 
    $email_message = "Form details below.\n\n";
 
     
 
    function clean_string($string) {
 
      $bad = array("content-type","bcc:","to:","cc:","href");
 
      return str_replace($bad,"",$string);
 
    }
 
     
 
    $email_message .= "First Name: ".clean_string($first_name)."\n";
 
    $email_message .= "Last Name: ".clean_string($last_name)."\n";
 
    $email_message .= "Email: ".clean_string($email_from)."\n";
 
    //$email_message .= "Telephone: ".clean_string($telephone)."\n";
 
    $email_message .= "Message: \r\n \r\n".clean_string($comments)."\n";
 
     

 
//  TEST OF THE MAIL SYSTEM USING PHP mail() 

$from = "admin@bbqfrc.x10host.com"; 
ini_set("SMTP","xo4.x10hosting.com:465" ); 
$to="bbqfirst15@gmail.com";  
$subject = "**BBQ FIRST QUERY**";

$headers = "Content-type: text/plain; charset=windows-1251 \r\n"; 
$headers .= "From: $from\r\n"; 
$headers .= "Reply-To: ".$email_from."\r\n"; 
$headers .= "MIME-Version: 1.0\r\n"; 
$headers .= "X-Mailer: PHP/" . phpversion(); 
if( mail($to, $subject, $email_message, $headers)  ){ 
    include "email_confirm.php";
} else { 
   //include "email_confirm.php";
   //echo $resp;
} 

?>
 
 
 
<!-- include your own success html here 
 
Thank you for contacting us. We will be in touch with you very soon.
 
 -->
