<?php include_once("contact.html");

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email_subject = "New Contact Submission";
    $mailFrom = $_POST['mail'];
    $phoneNumber = $_POST['phone'];
    $postCode = $_POST['postcode'];
    $message = $_POST['message'];
    
    $to = "nick@mg.dunedinkickboxing.com";
    $headers = "From: ".$mailFrom;
    $txt = "You have recieved a new Email from ".$name.".\n\n".$message;
    
    mail($to, $email_subject, $txt, $headers)or die ("Error!");
    echo("<p>Thanks for getting in touch, we'll get back to you shortly..</p>");
    header("Location: index.php?mailsend");
}

?>
     
<?php
if($_POST)
{
require('contactform.php');
    
    $name      = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $mailFrom     = filter_var($_POST["mail"], FILTER_SANITIZE_EMAIL);
    $phoneNumber     = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $postCode     = filter_var($_POST["postcode"], FILTER_SANITIZE_STRING);
    $messsage   = filter_var($_POST["message"], FILTER_SANITIZE_STRING);
    
    if(empty($name)) {
		$empty[] = "<b>Name</b>";		
	}
	if(empty($emailFrom)) {
		$empty[] = "<b>Email</b>";
	}
	if(empty($phoneNumber)) {
		$empty[] = "<b>Phone Number</b>";
	}	
	if(empty($message)) {
		$empty[] = "<b>Comments</b>";
	}
	
	if(!empty($empty)) {
		$output = json_encode(array('type'=>'error', 'text' => implode(", ",$empty) . ' Required!'));
        die($output);
	}
	
	if(!filter_var($emailFrom, FILTER_VALIDATE_EMAIL)){ //email validation
	    $output = json_encode(array('type'=>'error', 'text' => '<b>'.$emailFrom.'</b> is an invalid Email, please correct it.'));
		die($output);
	}
	
	//reCAPTCHA validation
	if (isset($_POST['g-recaptcha-response'])) {
		
		require('component/recaptcha/src/autoload.php');		
		
		$recaptcha = new \ReCaptcha\ReCaptcha(SECRET_KEY, new \ReCaptcha\RequestMethod\SocketPost());

		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

		  if (!$resp->isSuccess()) {
				$output = json_encode(array('type'=>'error', 'text' => '<b>Captcha</b> Validation Required!'));
				die($output);				
		  }	
	}
	
	$toEmail = "member@testdomain.com";
	$mailHeaders = "From: " . $user_name . "<" . $user_email . ">\r\n";
        $mailBody = "User Name: " . $user_name . "\n";
	$mailBody .= "User Email: " . $user_email . "\n";
	$mailBody .= "Phone: " . $user_phone . "\n";
	$mailBody .= "Content: " . $content . "\n";
	if (mail($toEmail, "Contact Mail", $mailBody, $mailHeaders)) {
	    $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_name .', thank you for the comments. We will get back to you shortly.'));
	    die($output);
	} else {
	    $output = json_encode(array('type'=>'error', 'text' => 'Unable to send email, please contact'.SENDER_EMAIL));
	    die($output);
	}
}
?>   
