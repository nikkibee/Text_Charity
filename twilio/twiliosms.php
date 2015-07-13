<?php
    session_start();
    require '../javascript/twilio/Twilio.php';
 
	// set your AccountSid and AuthToken from www.twilio.com/user/account
	$AccountSid = "AC98e444444b6b7b198bb5f2b3579f9763";
	$AuthToken = "7757683330eeabfd5f4b40194315e2aa";
	 
	$client = new Services_Twilio($AccountSid, $AuthToken);
	
	$from = $_REQUEST['From'];
	$body = $_REQUEST['Body'];  //name
	$to = $_REQUEST['To'];
	
	$message = "";
    
    class entry {
        public $from = "";
        public $charityId = "";
        public $charityName  = "";
		public $searchTerm = "";
    }
    $doc = $_SERVER['DOCUMENT_ROOT']."/battlehack2015/twilio/entries.json";
    $json = file_get_contents($doc);
    $entry = new entry();
    // Convert JSON string to Array
    $entryArray = json_decode($json, true);
    $length = count($entryArray);
	$counter = 0;
    $value = false; 
    for($i = 0; $i < $length; $i++) 
	{
        if($entryArray[$i]["from"] == $from) 
		{
            $entry->from = $entryArray[$i]["from"];
            $entry->charityId = $entryArray[$i]["charityId"];
            $entry->charityName = $entryArray[$i]["charityName"];
			$entry->searchTerm = $entryArray[$i]["searchTerm"];
            $value = true;
			$counter=2;
        }
    }
	
    if (strtoupper(trim($body)) == 'MORE')
	{
		$message = "Welcome to Text a Charity App.  Send us the name of the charity you wish to look for to get started.  May I suggest the you text us CANCER.  After we locate your charity, text us WEBSITE, EMAIL, DESCRIPTION, WHAT, WHY, DONATE for more information";
	}
	else if($value == false) 
	{
		$entry=LookUpCharity($body, $doc, $from);
		if (strlen($entry->charityId) == 0)
		{
			$message = "No charity found with the search term $body.  Please try a new search term with only a single word for most results.  If you're stuck, text us MORE";
		}
		else
		{
			$message = "Welcome to Text a Charity App.  You sent us $body.  You are set to the charity $entry->charityName.  If this is not what you are looking for, text us NEXT.    To get additional information, text us MORE";
		}
	}
	else 
	{
		$body = strtoupper($body);
		$body = trim($body);
		if ($body == 'WEBSITE')
		{
			$xml = GetCharityInformation($entry->charityId);
			$temp = $xml->websiteUrl;
			if ((strlen($temp) == 0) || empty($temp))
			{
				$message = "$entry->charityName has no website listed :(";
			}
			else
			{
				$message = "The website for $entry->charityName is $temp";
			}
		}
		else if ($body == 'NEXT')
		{
			$term = $entry->searchTerm;
			$next = $entry->charityId;
			$entry = LookUpNextCharity($term, $from, $next);
			if (strlen($entry->charityId) !== 0)
			{
				for($i = 0; $i < $length; $i++) 
				{
					if($entryArray[$i]["from"] == $from)
					{
						$entryArray[$i]["from"] = $entry->from;
						$entryArray[$i]["charityId"] = $entry->charityId;
						$entryArray[$i]["charityName"] = $entry->charityName;
						$entryArray[$i]["searchTerm"] = $entry->searchTerm;
						$updated = json_encode($entryArray);
						file_put_contents($doc, $updated);
					}
				}
				$message = "We found the charity $entry->charityName.  If this is not what you are looking for, text us NEXT again.";
			}
			else
			{
				$message = "Could not locate any more charities with the search term $term";
			}
		}
		else if ($body == 'CATEGORIES')
		{
			$xml = GetCharityInformation($entry->charityId);
			$val = $xml->categories;
			$temp = implode(", ", $val);
			if ((strlen($temp) == 0) || empty($temp))
			{
				$message = "$entry->charityName has no categories listed :(";
			}
			else
			{
				$message = "The categories for $entry->charityName are $temp";
			}
		}
		else if ($body == 'EMAIL')
		{
			$xml = GetCharityInformation($entry->charityId);
			$temp = $xml->emailAddress;
			if ((strlen($temp) == 0) || empty($temp))
			{
				$message = "$entry->charityName has no email listed :(";
			}
			else
			{
				$message = "The email for $entry->charityName is $temp";
			}
		}
		else if ($body == 'DESCRIPTION')
		{
			$xml = GetCharityInformation($entry->charityId);
			$temp = $xml->description;
			if ((strlen($temp) == 0) || empty($temp))
			{
				$message = "$entry->charityName has no description listed :(";
			}
			else
			{
				$message = "The description for $entry->charityName is $temp";
			}
		}
		else if ($body == 'WHAT')
		{
			$xml = GetCharityInformation($entry->charityId);
			$temp = $xml->impactStatementWhat;
			if ((strlen($temp) == 0) || empty($temp))
			{
				$message = "$entry->charityName has not answered what they do :(";
			}
			else
			{
				$message = "The $entry->charityName does What? $temp";
			}
		}
		else if ($body == 'WHY')
		{
			$xml = GetCharityInformation($entry->charityId);
			$temp = $xml->impactStatementWhy;
			if ((strlen($temp) == 0) || empty($temp))
			{
				$message = "$entry->charityName has not answered why they exist :(";
			}
			else
			{
				$message = "The $entry->charityName exists Why? $temp";
			}
		}
		else if ($body == 'DONATE')
		{
			$message= "Go here to donate to $entry->name http://www.justgiving.com/4w350m3/donation/direct/charity/$entry->charityId";
		}
		else
		{
			if ($value == true)
			{
				$counter=4;
				$entry = LookUpCharity($body, $doc, $from);
				for($i = 0; $i < $length; $i++) {
					if($entryArray[$i]["from"] == $from) 
					{
						$entryArray[$i]["from"] = $entry->from;
						$entryArray[$i]["charityId"] = $entry->charityId;
						$entryArray[$i]["charityName"] = $entry->charityName;
						$entryArray[$i]["searchTerm"] = $entry->searchTerm;
						$counter=6;
						$updated = json_encode($entryArray);
						file_put_contents($doc, $updated);
						if (strlen($entry->charityId) == 0)
						{
							$message = "No charity found with the search term $body.  Please try another search term.  If you're stuck, text us MORE";
						}
						else
						{
							$message = "Welcome to Text a Charity App.  You sent us $body.  We found the charity $entry->charityName.  If this is not what you are looking for, text us NEXT.    To get additional information, text us MORE";
						}
					}
				}
			}
		}
	}
	
	function GetCharityInformation($id)
	{
		$xml = simplexml_load_file("http://suefeng.com/battlehack2015/getcharity.php?id=$id");
		return $xml;
	}
	
	function LookUpNextCharity($body, $from, $next)
	{
		$xml = simplexml_load_file("http://suefeng.com/battlehack2015/charityinfo.php?q=$body");
		$found = false;
        $nextResult = new entry();
		$nextResult->from = $from;
		$nextResult->searchTerm = $body;
        $results = $xml->charitySearchResults->charitySearchResult;
		$len = count($results);
        for($i =0; $i < $len; $i++)
        {
			$result = $results[$i];
            if ($result->charityId == $next)
            {
                $found = true;
            }
            else
            {
                if ($found==true)
                {
                    $nextResult->charityId = (string)$result->charityId;
					$nextResult->charityName = (string)$result->name;
					return $nextResult;
                }
            }
        }
		
		return $nextResult;
    }
	
	function LookUpCharity($body, $doc, $from)
	{
		$xml = simplexml_load_file("http://suefeng.com/battlehack2015/charityinfo.php?q=$body");
		$entry = new entry();
		$entry->charityId = (string)$xml[0]->charitySearchResults->charitySearchResult->charityId;
		$entry->charityName = (string)$xml[0]->charitySearchResults->charitySearchResult->name;
		$entry->from = $from;
		$entry->searchTerm = $body;
		if (strlen($entry->charityId) !== '')
		{
			$outputstring = json_encode($entry);
			//first, obtain the data initially present in the text file
			$ini_handle = fopen($doc, "r");
			$ini_contents = str_replace('[', '', fread($ini_handle, filesize($doc)));
			fclose($ini_handle);
			//done obtaining initially present data

			//write new data to the file, along with the old data
			$handle = fopen($doc, "w+");
			$writestring = "[" . strip_tags(stripslashes($outputstring)) . "," . $ini_contents;
			if (fwrite($handle, $writestring) === false) {
			}
			else { }
			fclose($handle);
		}
		return $entry;
    }
?>
<Response>
    <Message><?php echo $message ?></Message>
</Response>
