?php
    require '../javascript/twilio/Twilio.php';
 
	// set your AccountSid and AuthToken from www.twilio.com/user/account
	$AccountSid = "AC98e444444b6b7b198bb5f2b3579f9763";
	$AuthToken = "7757683330eeabfd5f4b40194315e2aa";
	 
	$client = new Services_Twilio($AccountSid, $AuthToken);
	 
	$message = $client->account->messages->create(array(
		"From" => "309-929-4615",
		"To" => "312-952-6796",
		"Body" => "shhh the baby is sleeping",
	));
	 
	// Display a confirmation message on the screen
	echo "Sent message {$message->sid}";

?>
