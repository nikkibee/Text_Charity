<?php
    require '../javascript/twilio/Twilio.php';
 
	// set your AccountSid and AuthToken from www.twilio.com/user/account
	$AccountSid = "AC98e444444b6b7b198bb5f2b3579f9763";
	$AuthToken = "7757683330eeabfd5f4b40194315e2aa";
	 
	$client = new Services_Twilio($AccountSid, $AuthToken);
	 
	// foreach ($client->account->messages->getIterator(0, 50, array('To' => '+13099294615',
			// )) as $message) {
		// echo "From: {$message->from}\nTo: {$message->to}\nBody: " . $message->body;
		// }
	
	$from = $_REQUEST['From'];
	$body = $_REQUEST['Body'];  //country + name
	$to = $_REQUEST['To'];
	$charity = 'Girl Scouts';
	$id = 2050;
	
	$line = "Welcome to Text a Charity App.  Please send us the name of the charity to start";
	
	$xml = simplexml_load_file("http://suefeng.com/battlehack2015/getcharity.php?id=$id");
	
	if (trim($body) == 'WEBSITE')
		$line = "$charity 's website is $xml->websiteUrl";
	else if (trim($body) == 'EMAIL')
		$line = "$charity 's website is $xml->emailAddress";
	else if (trim($body) == 'DESCRIPTION')
		$line = "$charity 's website is $xml->description";
	else if (trim($body) == 'DONATE')
		//call url for this here
		$line = "http
	
	//echo "From: {$message->from}\nTo: {$message->to}\nBody: " . $message->body;
	
	//WEBSITE, EMAIL, DESCRIPTION, MOTTO, DONATE
