 <?php
 	// script to validate recaptcha, then send email if recaptcha passes
 	require_once('recaptchalib.php');
 
 	$privateKey = "6Lc4XbsSAAAAAGqqoE92FrwfmfllV58xPm16eVIZ";

 	// do the recaptcha validation
 	$response = recaptcha_check_answer(
	                           $privateKey,
                               $_SERVER["REMOTE_ADDR"],
                               $_POST["recaptcha_challenge_field"],
                               $_POST["recaptcha_response_field"]);
    
    $emailSent = false;
    
    // if recaptcha passed send email, otherwise return error
 	if (isValid($response)) {
 		$emailSent = sendEmail();
 	} else {
		$errorCode = $response->error;
 		displayError($errorCode);
 	}
 	
 	// check mail send was successful
	if ($emailSent) {
		displayThanks();	
	} else {
		// not handling the email error on the contact form a this time 
		die ("Sorry, the email system has encountered an error!" .
		  "Please return to <a href=\"http://lauraina-levwood.com\">lauraina-levwood.com</a><br/><br/>");
	}
  
	// -------   FUNCTIONS -----------
  
	function displayThanks() {
   		// redirect to thanks page
 		print "<meta http-equiv=\"refresh\" content=\"0;URL=..\\thanks.html\"\">";

 	}
 	
	function displayError($errorCode) {
	
   		// redirect back to contact page with error code in URL
   		$firstName = $_POST['firstName']; 
		$lastName = $_POST['lastName']; 
		$telephone = $_POST['phone']; 
		$email = $_POST['email']; 
		$how = $_POST['mailHow'];
		$message = $_POST['mailText'];
		$interest = $_POST['interest'];
		$available = $_POST['availability'];
		
		$interestParams = "";
		if (!empty($interest)) {

			$interestcount = count($interest);
			for($i=0; $i < $interestcount; $i++)
		    {
		      $interestParams .= '&interest[]='.$interest[$i];
		    }
		}

		$availabilityParams = "";
		if (!empty($available)) {

			$availabilitycount = count($available);
		    for($i=0; $i < $availabilitycount; $i++)
		    {
				$availabilityParams .= '&availability[]='.$available[$i];
		    }
		}
		
		
   		$parameters = "?errorCode=".$errorCode; 
   		$parameters .= "&firstName=".$firstName;
   		$parameters .= "&lastName=".$lastName;
   		$parameters .= "&phone=".$telephone;
   		$parameters .= "&email=".$email;
        $parameters .= "&mailHow=".$how;
   		$parameters .= "&mailText=".$message;
		$parameters .= $interestParams;
        $parameters .= $availabilityParams;
   		
   		print "<meta http-equiv=\"refresh\" content=\"0;URL=..\\contact.html".$parameters."#captcha_info\">";

 	} 
 
 
	function isValid($response) {
   		return $response->is_valid;
	}

 
	function sendEmail() {

		$emailTo = "aaron.brownlee@gmail.com, lauraina.rahimah@yahoo.com";
		$subject = "[lauraina-levwood] message from contact form";
		$headers = "From: do_not_reply@lauraina-levwood.com" . "\r\n" .
    		"Reply-To: do_not_reply@lauraina-levwood.com" . "\r\n" .
    		"X-Mailer: PHP/" . phpversion();
	
		$body = createBody();
		
		// send email 
		$success = mail($emailTo, $subject, $body, $headers, "-fdo_not_reply@lauraina-levwood.com");
		
		echo($body);	
			
		return $success;

	}
	
	
	function createBody() {
	
		$firstName = Trim(stripslashes($_POST['firstName'])); 
		$lastName = Trim(stripslashes($_POST['lastName'])); 
		$telephone = Trim(stripslashes($_POST['phone'])); 
		$email = Trim(stripslashes($_POST['email'])); 
		$how = Trim(stripslashes($_POST['mailHow']));
		$message = Trim(stripslashes($_POST['mailText'])); 
		
		$interest = "None Selected";
		$interestItems = $_POST['interest'];
		echo($interestItems);
		if (!empty($interestItems)) {
			
			$interestcount = count($interestItems);
			$interest = "";
			    for($i=0; $i < $interestcount; $i++)
			    {
			      $interest.= $interestItems[$i]." ";
			    }
		}
		
		$availability = "None Selected";
		$availabilityItems = $_POST['availability'];
		echo($availabilityItems);
		if (!empty($availabilityItems)) {
			
			$availabilitycount = count($availabilityItems);
			$availability = "";
			    for($i=0; $i < $availabilitycount; $i++)
			    {
			      $availability.= $availabilityItems[$i]." ";
			    }
		}

		// prepare email body text
		$body = "The following was sent from the contact form at lauraina-levwood.com";
		$body .= "\n------------------------------------------------\n";
		$body .= "Name: ";
		$body .= $firstName." ".$lastName;
		$body .= "\n";
		$body .= "Telephone: ";
		$body .= $telephone;
		$body .= "\n";
		$body .= "Email: ";
		$body .= $email;
		$body .= "\n";
		$body .= "Interests: ";
		$body .= $interest;
		$body .= "\n";
		$body .= "Availability: ";
		$body .= $availability;
		$body .= "\n";
		$body .= "How: ";
		$body .= $how;
		$body .= "\n";
		$body .= "Message: ";
		$body .= $message;
		$body .= "\n";
		
		return $body;
	}
		
?>