<?php header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$query= '';
$country='';
if (ISSET($_GET['q']))
{
	$query = $_GET['q'];
}

if (ISSET($_GET['country']))
{
	$country = $_GET['country'];
}


function GetCharities($name)
{
	$curl = curl_init();
	
	$url = "https://api-sandbox.justgiving.com/a5f9a931/v1/charity/search?q=$name";
	
	curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

echo GetCharities($query);


?>
