<?php header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$id='';
if (ISSET($_GET['id']))
{
	$id = $_GET['id'];
}

function GetCharityById($value)
{
	$curl = curl_init();
	
	$url = "https://api-sandbox.justgiving.com/a5f9a931/v1/charity/$value";
	
	curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

echo GetCharityById($id);

?>
