<?php
require_once("DbHandler.php");

setlocale(LC_TIME,"en_US");

$dbhandler = new DbHandler();

	
if (isset($_GET['tweet_id']))  // Invoked by the JavaScript function likeHandler.
                               // Returns the new num of likes of the tweet with twID = $_GET['tweet_id']
{
	header('Content-type: text/plain');
	echo $dbhandler->likeTweet($_GET['tweet_id']);
	exit;
}

switch ($_SERVER['REQUEST_METHOD']) {

	case 'POST': 
		
		// To be implemented (See Task #2)
		$author = $_POST["author"];
		$tweettext = $_POST["tweet_text"];
		$dbhandler->insertTweet($author, $tweettext);

		break;
		
	case 'PUT':
		
		// To be implemented (See Task #4)
		$result = file_get_contents('php://input');
		$twt = new SimpleXMLElement($result);
		$author = $twt->author;
		$text = $twt->text;
		$idTwt= $dbhandler->insertTweet($author, $text);
		$resp = new SimpleXMLElement('<?xml version="1.0"?>
		<response><tweetid author= "Test Author">'. $idTwt . '</tweetid></response>');

		echo $resp->asXML();
        exit;
		
	case 'DELETE':
	   
	    // To be implemented (See Task #5)
		$twtid= $_GET["twtid"];
		$resBorrat= $dbhandler->deleteTweet($twtid);
		$result = "fail";
		if ($resBorrat == 1) $result = "success";
		$resp = new SimpleXMLElement('<response><deletion id="' . $twtid . '">' . $result . '</deletion></response>');
		echo $resp->asXML();
		exit;
		
} 


if (!isset($_SERVER['HTTP_ACCEPT']) || !strpos($_SERVER['HTTP_ACCEPT'],"html")) // If $_SERVER['HTTP_ACCEPT'] does not contain "text/html"
                                                                                     // then return XML response				 
{
	$resp = new SimpleXMLElement("<alltweets></alltweets>");
	$resp->addAttribute('version', '0.1');
	
	$res = $dbhandler->getTweets();
	foreach($res as $tweet) {
		$item = $resp->addChild('tweet');
		$item->addAttribute('id', $tweet['id']);
		$item->author = $tweet['author'];
		$item->text = $tweet['text'];
		$item->numlikes = $tweet['likes'];
		$item->time = date(DATE_W3C,$tweet['time']);
	}
	
	header('Content-type: text/xml');
	echo $resp->asXML();
	
} 
else 
{ // Otherwise, return HTML response
	?>
	<html>
	<head><title>Wall of Tweets 2</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<link href="wotstyle.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" language="JavaScript">
	function likeHandler(id) {
		var target = 'showLikes_'+id;
		var uri = 'wall.php?tweet_id='+id;
		var req = new XMLHttpRequest();
		req.open("GET", uri, /*async*/true);
		req.onreadystatechange = function() {
			if (req.readyState == 4 && req.status==200) {
				document.getElementById(target).innerHTML = req.responseText;
			}
		};
		req.send(/*no params*/null);
	}
	</script>
	</head>
	<body class="wotbody">

	<h1 class="wottitle">Wall of Tweets 2</h1>
	<p align="middle">Get the RSS feed <a href="rss.php">here</a></p>

	<div class="walltweet">
	<form action="wall.php" method="post">
	<table border=0 cellpadding=2>
	<tr><td>Your name:</td><td><input name="author" type="text" size=70></td><td/></tr>
	<tr><td>Your tweet:</td><td><textarea name="tweet_text" rows="2" cols="70" wrap></textarea></td>
	<td><input type="submit" name="action" value="Tweet!"></td></tr>
	</table></form></div>

	<?php

	$current_date = idate('z',time());
	$res = $dbhandler->getTweets();

	foreach($res as $tweet)
	{ 
	  $day = idate('z',$tweet["time"]);
	  if ($current_date != $day) {
		  echo "<br><h3>...... ".strftime("%A, %B %d, %Y", $tweet["time"])."</h3>\n";
		  $current_date = $day;
		  }
	  $tweetid = $tweet["id"];
	  echo "<div class=\"wotitem\" id=\"item_$tweetid\">\n";
	  echo "<div class=\"likes\">\n";
	  $target = "showLikes_".$tweetid;
	  echo "<span class=\"numlikes\" id='".$target."'>".$tweet["likes"]."</span><br/><span class=\"smallfont\">people like this<span><br/><br/>\n";
	  echo "<button onclick=\"likeHandler('".$tweetid."')\">like</button><br/>\n";
	  echo "</div>\n";
	  echo "<div class=\"item\">\n";
	  echo "<h4><em>".$tweet["author"]."</em> @ ".date("H:i", $tweet["time"])."</h4>\n";
	  echo "<p>".$tweet["text"]."</p>";
	  echo "</div>\n";
	  echo "</div>\n";
	}
    echo "</body></html>";
}
?>
