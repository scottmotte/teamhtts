<?php

$apiKey = 'getyourapikey';
$listId = 'getyourlistid';
$double_optin=false;
$send_welcome=false;
$email_type = 'html';
$email = ($_GET['emailsub']) ?$_GET['emailsub'] : $_POST['emailsub'];
//replace us2 with your actual datacenter
$submit_url = "http://us7.api.mailchimp.com/1.3/?method=listSubscribe";
$data = array(
    'email_address'=>$email,
    'apikey'=>$apiKey,
    'id' => $listId,
    'double_optin' => $double_optin,
    'send_welcome' => $send_welcome,
    'email_type' => $email_type
);


//flag to indicate which method it uses. If POST set it to 1
if ($_POST) $post=1;


//if the errors array is empty, send the mail
if (!$errors) {

	$payload = json_encode($data);
 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $submit_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode($payload));
	 
	 //send the mail
	$result = curl_exec($ch);
	curl_close ($ch);
	$data = json_decode($result);
	
	if ($result){
	$result = 1;	
	}
	
	//if POST was used, display the message straight away
	if ($_POST) {
		if ($result) echo 'Thank you! We have received your message.';
		else echo 'Sorry, unexpected error. Please try again later';
		
	//else if GET was used, return the boolean value so that 
	//ajax script can react accordingly
	//1 means success, 0 means failed
	} else {
		echo $result;	
	}

//if the errors array has values
} else {
	//display the errors message
	for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
	echo '<a href="form.php">Back</a>';
	exit;
}



?>
