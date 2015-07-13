<html>
<head>
<meta charset="UTF-8">
<title>Text a Charity</title>
<?php include('includes/header.php'); ?>
</head>
<body>
<header>
    <h1><a href="/battlehack2015">Text to Charity</a></h1>
</header>
<div class="results">
    <div class="wrapper">
    <p>
    <?php
    $id='';
    $searchResult = '';
    if (ISSET($_GET['id']))
    {
        $id = $_GET['id'];
    }
    if(isset($_GET['q'])) 
    {
        $searchResult = $_GET['q'];
    }
    $xml = simplexml_load_file("http://suefeng.com/battlehack2015/getcharity.php?id=$id");
    if($xml->name == '') {
        echo 'Sorry but the charity you entered is not there. <a href="/dispcharity.php?q='.$searchResult.'">Please try another</a> or <a href="/battlehack2015">search again</a>.';
    }
    else {
        echo '<div class="image">';
        echo '<img src="'.$xml->logoAbsoluteUrl.'" alt="" class="logo">';
        echo '</div>';
        echo '<div class="charity-blurb">';
        echo '<h2><a href="'.$xml->websiteUrl.'" title="visit their website" target="_blank">'.$xml->name.'</a></h2>';
        echo '<strong>Country Code:</strong> '.$xml->countryCode.'<br>';
        echo '<strong>Charity Description:</strong> '.$xml->description.'<br>';
        echo '<strong>Email-Address:</strong> <a href="mailto:'.$xml->emailAddress.'">'.$xml->emailAddress.'</a><br>';
        echo '<strong>What Statement:</strong> '.$xml->impactStatementWhat.'<br>';
        echo '<strong>Why Statement:</strong> '.$xml->impactStatementWhy.'<br>';
        echo '<strong>Thank you Statement:</strong> '.$xml->thankyouMessage.'<br>';
        echo '</div>';
    ?>
    </p>
    </div>
</div>
<div class="donate">
    <p class="wrapper"><span>Would like to help?<br>Please feel free to donate.</span>
<?php 
function generatedonation($value)
{
	 $result= "http://www.justgiving.com/4w350m3/donation/direct/charity/$value";

    return $result;
}

$donation = generatedonation($id);

?>    
    <a href="<?php echo $donation; ?>" target="_blank" class="button">Donate</a></p>
    <?php } // closing the else statement ?>
</div>
</body>
</html>
