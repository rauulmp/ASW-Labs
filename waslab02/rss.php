<?php

require_once("DbHandler.php");

setlocale(LC_TIME,"en_US");

$dbhandler = new DbHandler();

$resp = new SimpleXMLElement("<rss></rss>");
	$resp->addAttribute('version', '2.0');
	$resp->addChild('channel');
	$resp->channel->addChild('title', "Wall of Tweets 2 - RSS Version");
	$resp->channel->addChild('link',"http://localhost:8080/waslab02/wall.php");
	$resp->channel->addChild('description', 'RSS 2.0 feed that retrieves the tweets posted to the web app "Wall of Tweets 2"');
	$twts = $dbhandler->getTweets();
	foreach($twts as $tweet) {
		$item = $resp->channel->addChild('item');
		$item->addChild('title', $tweet['text']);
		$item->addChild('link', "http://localhost:8080/waslab02/wall.php#item_" . $tweet['id']);
		$item->addChild('date', date(DATE_W3C,$tweet['time']));
		$item->addChild('description','This is WoT tweet #' . $tweet['id'] . ' posted by <i><b>' . $tweet['author'] . '</b></i>. It has been liked by <b>' . $tweet['likes'] . '</b> people');
	}
	
	header('Content-type: text/xml');
	echo $resp->asXML();

?>
