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
    $searchResult = '';
    if(isset($_GET['q'])) 
    {
        $searchResult = $_GET['q'];
    }
    $xml = simplexml_load_file("http://suefeng.com/battlehack2015/charityinfo.php?q=$searchResult");
    $results = $xml->charitySearchResults->charitySearchResult;
    $length = count($results);
    if($searchResult == '') {
        echo 'Sorry but the charity you entered is not there. Please <a href="/battlehack2015">search again</a>.';
    }
    else {
        for ($x=0; $x<$length; $x++) {
            echo '<div class="image">';
            $url = $results[$x]->logoUrl;
            echo '<img src="'.$url.'" alt="" class="logo">';
            echo '</div>';
            echo '<div class="charity-blurb">';
            echo '<h2><a href="dispcharity.php?id='.$results[$x]->charityId.'">'.$results[$x]->name.'</a></h2>';
            echo '<p>';
            echo $results[$x]->description.'<br><br>';
            echo '</p>';
            echo '</div>';
          
        }
    }
    ?>
    </p>
    </div>
</div>

