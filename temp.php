$length = count($entryArray);
    $value = false; 
	echo "start";
    for($i = 0; $i < $length; $i++) 
	{
        if($entryArray[$i]["from"] == $from) 
		{
            $entry->from = $entryArray[$i]["from"];
            $entry->charityId = $entryArray[$i]["charityId"];
            $entry->charityName = $entryArray[$i]["charityName"];
			$entry->searchTerm = $entryArray[$i]["searchTerm"];
            $value = true;
        }
    }
	var_dump($value);
    if (strtoupper(trim($body)) == 'MORE')
	{
		$message = "Welcome to Text a Charity App.  Send us the name of the charity you wish to look for to get started.  May I suggest the Cure for cancer.  After we locate your charity, text us WEBSITE, EMAIL, DESCRIPTION, WHAT, WHY, DONATE for more information";
	}
	else 
	{
		if($value == false) 
		{
			
		}
		else 
		{
			if (strtoupper(trim($body))  == 'NEXT')
				{
					echo "Next!";
				$term = $entry->searchTerm;
				$next = $entry->charityId;
				$entry = LookUpNextCharity($term, $from, $next);
				if (strlen($entry->charityID) !== 0)
				{
					echo "hi!";
					var_dump($entry);
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
		echo $next;
		echo $body;
		$results = $xml->charitySearchResults->charitySearchResult;
		$len = count($results);
		echo $len;
        for($i =0; $i < $len; $i++)
        {
			$result = $results[$i];
			echo "charityID = $result->charityId";
			if ($result->charityId == $next)
            {
				echo "match";
                $found = true;
            }
            else
            {
                if ($found==true)
                {
					echo "found";
                    $nextResult->charityId = (string)$result->id;
					$nextResult->charityName = (string)$result->name;
					var_dump($nextResult);
					return $nextResult;
                }
            }
        }
		
		return $nextResult;
    }
?>
